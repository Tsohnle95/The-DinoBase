<?php

require_once '../private/authentication.php';
require_once '../private/validation.php';

require_login();

$title = "View a Dino";
$introduction = "To view a dino in our database, click 'View' beside the dino you would like to see more information about.";
include 'includes/header.php';


$dino_id = $_GET['id'] ?? '';
$dino_id = filter_var($dino_id, FILTER_VALIDATE_INT);


if ($dino_id) {
    $dino = select_dino_by_id($dino_id);
} else {
    $dino = null;
}


if (!$dino) {
    echo "<div class=\"alert alert-danger\">Dino not found.</div>";
    include 'includes/footer.php';
    exit;
}

?>
<?php

    $show_full = $dino['url'] ?? 'placeholder.avif';

    if (!empty($show_full)) : ?>
        <div class="mb-3">
            <img src="../data/img/full/<?= htmlspecialchars($show_full); ?>" alt="Dino full image" style="max-width:100%; height:auto;">
        </div>
    <?php endif; ?>

<?php

$row = $dino;
$year = $dino['year'] ?? 0;
$size = $dino['size'] ?? 0;
$weight = $dino['weight'] ?? 0;
$continent = $continents_array[$dino['continent']] ?? 'Unknown';
$country = $dino['country'] ?? 'Unknown';
$time_period = $dino['time_period'] ?? 'Unknown';
$description = $dino['description'] ?? 'No description available';

include 'includes/dino-card.php';
?>

<?php include 'includes/footer.php'; ?>