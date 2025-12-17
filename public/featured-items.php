<?php
require_once '../private/authentication.php'; 

 $sql = "SELECT * FROM dinobase WHERE is_alive = 1";
 $title = "Featured Dinos";
 $introduction = "Welcome to the featured page. This table is for all living dinos and their remaining ancestors. Any dino that's marked as alive will appear here.";

include 'includes/header.php';

?>

<?php
//This was a mostly copy and paste from index.php with a swapped prepared statement. I'm some glad we made everything as prepared functions for this project. -Rodney 

  $user_fav_ids = [];
    if (isset($_SESSION['user_id'])) {
        $fav_rows = get_fav_dinos($_SESSION['user_id']);
        foreach ($fav_rows as $f) {
            $user_fav_ids[] = $f['id'];
        }
    }

echo generate_table(get_featured_dinos(), function ($row) use ($user_fav_ids) {
        $view = '<a href="view.php?id=' . $row['id'] . '" class="btn btn-success me-1">View</a>';
        $fav_button = '';
        if (isset($_SESSION['user_id'])) {
            $action = in_array($row['id'], $user_fav_ids) ? 'remove' : 'add';
            $label = $action === 'add' ? '<i class="bi bi-heart"></i>' : '<i class="bi bi-heart-fill"></i>';
            $fav_button = '<form method="POST" action="favourites.php" style="display:inline">'
                . '<input type="hidden" name="dino_id" value="' . $row['id'] . '">' 
                . '<input type="hidden" name="action" value="' . $action . '">' 
                . '<button class="btn btn-outline-primary" type="submit">' . $label . '</button>'
                . '</form>';
        }

        return $view . $fav_button;
    });
?>

<?php
include 'includes/footer.php'
?>