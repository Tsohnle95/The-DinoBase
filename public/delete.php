<?php

require_once '../private/authentication.php';
require_login();

$title = "Delete a Dino";
$introduction = "To delete a dino to our database, click 'Delete' beside the dino you would like to remove. You will then be taken to a confirmation page where you can complete the deletion process.";
include 'includes/header.php';

echo "<h2 class=\"fw-light mb-3\">Current Dinos in our Database</h2>";

// We've modified this function so that we can include a callback function. This will allow us to generate all of the unique links and query strings we need to take the user to the delete confirmation page with the information for the city they would like to delete. 
generate_table(get_user_dinos($_SESSION['user_id']), function ($dino) {
    // These variables will only have values assigned to them when the parent function, generate_table(), is called. This is because they are assigned by extracting each record in the foreach() loop.
    $dino_id = $dino['id'];
    $dino_name = $dino['genus_name'] . ' ' . $dino['species_name'];

    return "<a href=\"delete-confirmation.php?dino=" . urlencode($dino_id) . "&dino_name=" . urlencode($dino_name) . "\" class=\"btn btn-danger\"><i class=\"bi bi-trash3\"></i></a>";
});

include 'includes/footer.php'; ?>

