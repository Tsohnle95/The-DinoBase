<?php

require_once '../private/authentication.php';
require_once '../private/prepared.php';
require_once '../private/validation.php';

require_login();

// Check if an ID is provided
$dino_id = $_GET['id'] ?? $_POST['id'] ?? '';
$dino_id = filter_var($dino_id, FILTER_VALIDATE_INT);

if ($dino_id) {
    $dino = select_dino_by_id($dino_id);
    if (!$dino || $dino['user_id'] != $_SESSION['user_id']) {
        header("Location: edit.php");
        exit;
    }
    $title = "Edit a Dino";
    $introduction = "Edit the details of this dinosaur.";
    $mode = 'edit';
} else {
    $title = "My Dinosaurs";
    $introduction = "Manage your dinosaur entries.";
    $mode = 'list';
}

include 'includes/header.php';
include 'includes/upload.php';

if ($mode === 'edit') {
    // Here, we'll initialise the variables for all of the pre-existing values for the city.
    $existing_genus_name = $dino['genus_name'] ?? '';
    $existing_species_name = $dino['species_name'] ?? '';
    $existing_status = $dino['status'] ?? '';
    $existing_year = $dino['year'] ?? '';
    $existing_size = $dino['size'] ?? '';
    $existing_weight = $dino['weight'] ?? '';
    $existing_continent = $dino['continent'] ?? '';
    $existing_country = $dino['country'] ?? '';
    $existing_time_period = $dino['time_period'] ?? '';
    $existing_description = $dino['description'] ?? '';
    $existing_type = $dino['type'] ?? '';
    $existing_is_alive = $dino['is_alive'] ?? '0';

    // Next, we'll define the variables for all of the values form the user (i.e. whatever they give us in the form).
    $user_genus_name = $_POST['genus_name'] ?? '';
    $user_species_name = $_POST['species_name'] ?? '';
    $user_status = $_POST['status'] ?? '';
    $user_year = $_POST['year'] ?? '';
    $user_size = $_POST['size'] ?? '';
    $user_weight = $_POST['weight'] ?? '';
    $user_continent = $_POST['continent'] ?? '';
    $user_country = $_POST['country'] ?? '';
    $user_time_period = $_POST['time_period'] ?? '';
    $user_description = $_POST['description'] ?? '';
    $user_type = $_POST['type'] ?? '';
    $user_is_alive = isset($_POST['alive']) ? $_POST['alive'] : '0';

    $message = "";
    $alert_class = "alert-danger";

    // If the user has submitted the form (hit 'save' after editing), we need to validate their input and try updating the record in the database.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validation_result = dinosaur_input($user_genus_name, $user_species_name, $user_status, $user_year, $user_size, $user_weight, $user_continent, $user_country, $user_time_period, $user_description, $user_type, $user_is_alive, $enumerated_array, $continents_array, $time_period_array, $dino_type);

        if ($validation_result['is_valid']) {
            $validated_data = $validation_result['data'];

            $old_filename = $dino['url'] ?? 'placeholder.avif';
            $new_filename = !empty($file_name_new) ? $file_name_new : $old_filename;

            if (!empty($file_name_new) && $file_name_new !== $old_filename && $old_filename !== 'placeholder.avif') {
                $full_image = __DIR__ . '/../data/img/full/' . $old_filename;
                $thumb_image = __DIR__ . '/../data/img/thumbs/' . $old_filename;
                
                if (file_exists($full_image)) {
                    unlink($full_image);
                }
                if (file_exists($thumb_image)) {
                    unlink($thumb_image);
                }
            }

            // If the data is good, we will try to update.
            if (update_dino($validated_data['genus_name'], $validated_data['species_name'], $validated_data['status'], $validated_data['year'], $validated_data['size'], $validated_data['weight'], $validated_data['continent'], $validated_data['country'], $validated_data['time_period'], $validated_data['description'], $validated_data['type'], $validated_data['is_alive'], $dino_id, $new_filename)) {
                $alert_class = "alert-success";
                $message = $validated_data['genus_name'] . " " . $validated_data['species_name'] . " was updated successfully.";
                $dino = select_dino_by_id($dino_id);
            } else {
                $message = "There was an error updating the dino.";
            }
        } else {
            // If the data is not valid, we'll give the user errors.
            $message = implode("</p><p>", $validation_result['errors']);
        }
    }

    // This is our validation message block.
    if ($message != ""): ?>
        <div class="alert <?= $alert_class; ?> my-5" role="alert">
            <p><?= $message; ?></p>
        </div>
    <?php endif; ?>

    <h2 class="fw-light mb-3">Editing <?= $existing_genus_name . " " . $existing_species_name; ?></h2>
    <?php
    $show_full = !empty($file_name_new) ? $file_name_new : ($dino['url'] ?? 'placeholder.avif');
    if (!empty($show_full)) : ?>
        <div class="mb-3">
            <img src="../data/img/full/<?= htmlspecialchars($show_full); ?>" alt="Dino full image" style="max-width:100%; height:auto;">
        </div>
    <?php endif; ?>
    <?php include 'includes/form.php'; ?>

<?php } else {
    $user_dinos = get_user_dinos($_SESSION['user_id']);
    if (empty($user_dinos)) {
        echo '<p>You have not added any dinosaurs yet. <a href="add.php">Add one now</a>.</p>';
    } else {
        echo '<h2>Your Dinosaurs</h2>';
        echo generate_table($user_dinos, function ($dino) {
            $view = '<a href="view.php?id=' . $dino['id'] . '" class="btn btn-success me-1">View</a>';
            $edit = '<a href="edit.php?id=' . $dino['id'] . '" class="btn btn-primary me-1">Edit</a>';
            $delete = '<a href="delete-confirmation.php?dino=' . $dino['id'] . '&dino_name=' . urlencode($dino['genus_name'] . ' ' . $dino['species_name']) . '" class="btn btn-danger">Delete</a>';
            return $view . $edit . $delete;
        });
    }
}

include 'includes/footer.php'; ?>