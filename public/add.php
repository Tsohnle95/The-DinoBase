<?php

require_once '../private/authentication.php';
require_once '../private/validation.php';

require_login();

$title = "Add a Dino";
$introduction = "To add an entry to our DinoBase, simply fill out the form below and hit 'Save'.";
include 'includes/header.php';
include 'includes/upload.php';

$alive = isset($_POST['alive']) ? $_POST['alive'] : '0';

if (isset($_POST['submit'])) {
    $message = '';
    $alert_class = 'alert-danger';
    $validation_result = dinosaur_input($_POST['genus_name'], $_POST['species_name'], $_POST['status'], $_POST['year'], $_POST['size'], $_POST['weight'], $_POST['continent'], $_POST['country'], $_POST['time_period'], $_POST['description'], $_POST['type'], $alive, $enumerated_array, $continents_array, $time_period_array, $dino_type);

    if ($validation_result['is_valid']) {

        // In this case, the data passed our tests and is good to insert.
        $data = $validation_result['data'];

        // Here, we'll call our function to insert a city using a prepared statement.
                if (empty($file_name_new)) {
                    $file_name_new = 'placeholder.avif';
                }

        if (insert_dino($data['genus_name'], $data['species_name'], $data['status'], $data['year'], $data['size'], $data['weight'], $data['continent'], $data['country'], $data['time_period'], $data['description'], $data['type'], $data['is_alive'], $file_name_new, $_SESSION['user_id'])) {

            $message = "Dino added successfully!";
            $alert_class = "alert-success";

            // If we want, we can also clear our inputs after a successful insert to make sure the user doesn't spam-add a record. 
            // $city_name = $province = $population = "";

        } else {
            $message = "There was a problem adding the dino: " . $connection->error;
        }

    } else {
        // If the data is invalid, we'll show some errors.
        $message = implode("</p><p>", $validation_result['errors']);
    } // end of validation handling
} // end of 'if the user submitted the form data'

if (isset($message)) : ?>

<div class="alert p-3 <?= $alert_class; ?>" role="alert">
    <p><?= $message; ?></p>
</div>

<?php endif;

include 'includes/form.php';

include 'includes/footer.php'; ?>