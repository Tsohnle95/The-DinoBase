<?php

require_once '../private/authentication.php';

$title = "Public Page (Home)";
$introduction = "Welcome to our site! This is a public page that anyone can access. If you have an account, click the Login button to access your account page. If you're not logged in, you won't be able to access the admin area.";
include 'includes/header.php';

$per_page = $_POST['number-of-results'] ?? $_GET['number-of-results'] ?? 12;

$total_count = count_records();

// In our example, we have 152 records; divided by 12, that's 12.6666 ... pages. However, since you can't have a fraction of a page (we still need a complete page for those last few records), we must always round our quotient UP.
$total_pages = ceil($total_count / $per_page);

// Let's check to see if we're on a specific page. If this variable does not exist, we'll default to the first page.
$current_page = (int) ($_GET['page'] ?? 1);

// Because our vairables are being stored in a query string, we need to make sure the user hasn't mucked around with it or done anything funny. We cannot be on page 0 or lower, and we can't be on a page that is beyond the final page.
if ($current_page < 1 || $current_page > $total_pages || !is_int($current_page)) {
    $current_page = 1;
}

// The offset lets us know which set of records to grab. If we're on page 1, we ned numbers 1-12; if we're on page 2, we need 13-24 (which is offset by 12); if we're on page 3, we need 25-36 (which is offset by 24) ... and so on.
$offset = $per_page * ($current_page - 1);
?>

<!-- This form controls our pagination options. This will allow the user to choose how many records they want to see per page. -->
<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    <div class="input-group">
        <label for="number-of-results" class="input-group-text">Dinos per Page:</label>
        <select name="number-of-results" id="number-of-results" class="form-select" aria-label="Dinos per page">
            <!-- The array in our foreach loop will become the number of records the table can display. -->
            <?php foreach ([12, 24, 48] as $value): ?>
                <option value="<?= $value; ?>" <?= ($per_page == $value) ? 'selected' : ''; ?>><?= $value; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="submit-page-number" id="submit-page-number" value="Submit" class="btn btn-success">
    </div>
</form>

<?php

// We no longer need this query, as we have our custom (paginated) function.
// $sql = "SELECT `rank`, `country` FROM happiness_index;";
// $result = $connection->query($sql);

$result = find_records($per_page, $offset);

if ($connection->error): ?>

    <!-- If there's an issue, we'll display an error message to the user. -->
    <p>Oh no! There was an issue retrieving the data.</p>

<?php elseif ($result->num_rows > 0): ?>
    <?php

    $user_fav_ids = [];
    if (isset($_SESSION['user_id'])) {
        $fav_rows = get_fav_dinos($_SESSION['user_id']);
        foreach ($fav_rows as $f) {
            $user_fav_ids[] = $f['id'];
        }
    }

    echo generate_table($result->fetch_all(MYSQLI_ASSOC), function ($row) use ($user_fav_ids) {
        $view = '<a href="view.php?id=' . $row['id'] . '" class="btn btn-success me-1">View</a>';
        $edit = '';
        $fav_button = '';
        if (isset($_SESSION['user_id'])) {
            if ($row['user_id'] == $_SESSION['user_id']) {
                $edit = '<a href="edit.php" class="btn btn-primary me-1">My Dinosaurs</a>';
            }
            $action = in_array($row['id'], $user_fav_ids) ? 'remove' : 'add';
            $label = $action === 'add' ? '<i class="bi bi-heart"></i>' : '<i class="bi bi-heart-fill"></i>';
            $fav_button = '<form method="POST" action="favourites.php" style="display:inline">'
                . '<input type="hidden" name="dino_id" value="' . $row['id'] . '">' 
                . '<input type="hidden" name="action" value="' . $action . '">' 
                . '<button class="btn btn-outline-primary" type="submit">' . $label . '</button>'
                . '</form>';
        }

        return $view . $edit . $fav_button;
    }); ?>
    <!-- Pagination Navigation -->
    <nav aria-label="Page Number">
        <ul class="pagination justify-content-center">
            <!-- PREVIOUS: If the current page is greater than 1, we'll include the 'Previous' button. -->
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a href="index.php?page=<?= $current_page - 1; ?>&number-of-results=<?= $per_page; ?>"
                        class="page-link link-success">Previous</a>
                </li>
            <?php endif; ?>
            <!-- NUMBERED PAGES -->
            <?php

            // If we have massive amount of pages, we don't want to generate a link for each individual page. Instead, we want to obscure some these pages with a gap. The 'gap' in our case will be an ellipses (...).
            $gap = FALSE;

            // The window is how many pages on either side of the current page (or next/previous buttons) we would like to see or have generated in our loop.
            $window = 1;

            for ($i = 1; $i <= $total_pages; $i++) {
                /*
                    We're checking three conditions to see if a gap should be inserted here:

                        1. we're not near the beginning
                        2. we're not near the end
                        3. we're not nead the current page

                    If all three are 'true': this is a 'middle' page number that doesn't need to be shown.
                */

                if ($i > 1 + $window && $i < $total_pages - $window && abs($i - $current_page) > $window) {
                    if (!$gap): ?>
                        <li class="page-item"><span class="page-link link-success">...</span></li>
                    <?php endif;

                    // If we've inserted a gap (...), we need to flip this variable to TRUE so that we can carry on (and we don't insert more than one gap at once).
                    $gap = TRUE;
                    continue;
                }

                // After inserting the gap or render a visible page, the loop resets `$gap = FALSE` so that it knows it's sage to insert another ellipses the next time it skips over pages.
                $gap = FALSE;

                /*
                    After figuring out whether or not we need to print a gap and skip some pages, we have two possibilities when it comes to the numbered pages:

                    1. We print out the active page (a page we're currently on);
                    2. We print out an inactive page (a page we're NOT on).
                */

                if ($current_page == $i): ?>

                    <li class="page-item bg-success active">
                        <!-- We're using a moot value (placeholder value) so that the user doesn't accidentally click the current page and reload everything. -->
                        <a href="#" class="page-link bg-success link-white border border-success"><?= $i; ?></a>
                    </li>

                <?php else: ?>

                    <!-- This will be an 'inactive' page, or one that the user can navigate to. -->
                    <li class="page-item">
                        <a href="index.php?page=<?= $i; ?>&number-of-results=<?= $per_page; ?>"
                            class="page-link link-success"><?= $i; ?></a>
                    </li>

                <?php endif;
            } // this is the end of the for loop
        

            ?>

            <!-- NEXT: If the current page is less than the total number of pages, we'll include the 'Next' button. -->
            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a href="index.php?page=<?= $current_page + 1; ?>&number-of-results=<?= $per_page; ?>"
                        class="page-link link-success">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

<?php endif;

include 'includes/footer.php'; ?>