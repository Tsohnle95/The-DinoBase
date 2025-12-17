<?php

require_once '../private/authentication.php';
require_once '../private/validation.php';

require_login();

require_once dirname(__DIR__, 1) . '/data/connect.php';
$connection = db_connect();

require_once '../private/prepared.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dino_id = filter_input(INPUT_POST, 'dino_id');
    $action = filter_input(INPUT_POST, 'action');

    if ($dino_id && in_array($action, ['add','remove'])) {
        if ($action === 'add') {
            $check = execute_prepared_statement('SELECT COUNT(*) AS c FROM favourites WHERE dino_id = ? AND user_id = ?;', [$dino_id, $_SESSION['user_id']], 'ii');
            $row = $check->fetch_assoc();
            if ($row['c'] === 0) {
                 add_favourite($dino_id);
                 }
        } else {
            remove_favourite($dino_id);
        }
    }
    
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'favourites.php'));
    exit;
}

$username = $_SESSION['username'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

$title = $username . "'s Favourites";
$introduction = "Here are the dinos you have marked as favourites.";
include 'includes/header.php';

$favourite_dinos = get_fav_dinos($user_id);

if (empty($favourite_dinos)) {
    echo '<p class="lead">Let us know your favourite dinos</p>';
} else {
    echo generate_table($favourite_dinos, function ($row) {
        $view = '<a href="view.php?id=' . $row['id'] . '" class="btn btn-success me-1">View</a>';
        $remove = '<form method="POST" action="favourites.php" style="display:inline">'
			. '<input type="hidden" name="dino_id" value="' . $row['id'] . '">' 
			. '<input type="hidden" name="action" value="remove">' 
			. '<button class="btn btn-outline-danger" type="submit"><i class="bi bi-trash3"></i></button>'
			. '</form>';

		return $view . $remove;
	});
}

include 'includes/footer.php';
?>