<?php

require_once '../private/authentication.php';
require_login();

/**
 * There are three potential states this page can be in:
 * 
 * 1. The user has just selected a city to delete.
 * 2. The user has successfully deleted a city.
 * 3. Error state (there is information missing from the query string, or the deletion process went awry).
 */

$title = "Delete Confirmation";
$introduction = "";
include 'includes/header.php';

// Because we're using the GET method, remember that the user can muck around with the query string. To prevent any weirdness, we'll check to make sure the city ID is valid.
$dino_id = filter_input(INPUT_GET, 'dino', FILTER_VALIDATE_INT);
$dino_name = filter_input(INPUT_GET, 'dino_name', FILTER_SANITIZE_SPECIAL_CHARS);

$message = "";

if (!$dino_id || !$dino_name) {
    $message = "<p>Please return to the delete page and select an option from the table.</p>";
}

$favourite_count = 0;
$favourited_by = [];
if ($dino_id) {
    $favourite_count = (int) count_favourites_for_dino($dino_id);
    if ($favourite_count > 0) {
        $favourited_by = users_per_fav_dino($dino_id);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $hidden_id = filter_input(INPUT_POST, 'hidden-id', FILTER_VALIDATE_INT);
    $hidden_name = filter_input(INPUT_POST, 'hidden-name', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($hidden_id) {

        $dino = select_dino_by_id($hidden_id);
        if (!$dino) {
            $message = "Dino not found.";
        } elseif ($dino['user_id'] != $_SESSION['user_id']) {
            $message = "You do not have permission to delete this dino.";
        } else {
            $fav_count = count_favourites_for_dino($hidden_id);
            $ref_favourites = "";

            $connection->begin_transaction();
            try {
                if ($fav_count > 0) {
                    execute_prepared_statement('DELETE FROM favourites WHERE dino_id = ?;', [$hidden_id], 'i');
                    
                    $ref_favourites = " Any favourites referencing this dino were also removed.";
                }

                if ($dino && !empty($dino['url']) && $dino['url'] !== 'placeholder.avif') {
                    $full_image =  __DIR__ . '/../data/img/full/' . $dino['url'];
                    $thumb_image =  __DIR__ . '/../data/img/thumbs/' . $dino['url'];

                    if (file_exists($full_image)) {
                        unlink($full_image);
                    }
                    if (file_exists($thumb_image)) {
                        unlink($thumb_image);
                    }
                }

                delete_dino($hidden_id);

                $connection->commit();

                $message = "<p>" . urldecode($hidden_name) . " was deleted from the database." . $ref_favourites . "</p>";
                $dino_id = NULL;
            } catch (Exception $e) {
                $connection->rollback();
                $message = htmlspecialchars($e->getMessage());
            }
        }
    }
}

if ($message != "") : ?>

    <div class="alert alert-danger text-center" role="alert">
        <?= $message; ?>
    </div>

<?php endif;

// If the user has just selected a city from the delete page, they'll have the city_id in their query string. In that case, we'll give them the big red delete button.
if ($dino_id) : ?>

    <p class="text-danger lead mb-5 text-center">Are you sure that you want to delete <?= urldecode($dino_name); ?> ?</p>

    <?php if ($favourite_count > 0): ?>
        <div class="alert alert-warning text-center">
            <strong>Warning:</strong> <?= $favourite_count; ?> user(s) have this dino in their favourites. Deleting it will remove it from their favourites as well.
            <?php if (!empty($favourited_by)): ?>
                <div class="mt-2">Favourited by: <?= htmlspecialchars(implode(', ', $favourited_by)); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="text-center">
        <input type="hidden" name="hidden-id" id="hidden-id" value="<?= $dino_id; ?>">
        <input type="hidden" name="hidden-name" id="hidden-name" value="<?= $dino_name; ?>">

        <!-- Submit Button -->
        <input type="submit" name="confirm" id="confirm" value="Yes, I'm sure." class="btn btn-danger" 
        <?php if ($favourite_count > 0): ?>return "This dino is favourited by <?= $favourite_count; ?> user(s). Are you sure you want to delete it?";
        <?php endif; ?>>
    </form>

<?php endif; ?>

<a href="delete.php" class="text-link">Return to 'Delete A Dino' Page</a>

<?php include 'includes/footer.php'; ?>