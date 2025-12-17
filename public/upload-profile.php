<?php
require_once '../private/authentication.php';
require_once '../private/prepared.php';
require_login();

$title = 'Upload Profile Picture';
$introduction = 'Upload a profile picture to personalize your account.';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_pic'])) {
        $_FILES['img-file'] = $_FILES['profile_pic'];
    }

    include 'includes/upload.php';

    if (!empty($file_name_new)) {
        $old = profile_picture($_SESSION['user_id']);
        if ($old && $old !== 'placeholder.avif') {
            $full = __DIR__ . '/../data/img/full/' . $old;
            $thumb = __DIR__ . '/../data/img/thumbs/' . $old;
            if (file_exists($full)) unlink($full);
            if (file_exists($thumb)) unlink($thumb);
        }
        if (profile_picture($_SESSION['user_id'], $file_name_new)) {
            $message = 'Profile picture updated successfully.';
        } else {
            $message .= ' Saving to your profile failed.';
        }
    }
}

include 'includes/header.php';

if (!empty($message)) : ?>
    <div class="alert alert-info"><?= htmlspecialchars($message); ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="profile_pic" class="form-label">Select profile picture</label>
        <input type="file" name="profile_pic" id="profile_pic" accept=".png,.jpg,.jpeg,.webp,.avif" class="form-control">
        <p class="form-text">Max 2MB. Supported types: AVIF, JPG, JPEG, PNG, WebP.</p>
    </div>
    <button class="btn btn-primary" type="submit">Upload</button>
    <a href="index.php" class="btn btn-link">Cancel</a>
</form>

<h2 class="mt-5">Your Dinosaurs</h2>
<?php
$user_dinos = get_user_dinos($_SESSION['user_id']);
if (empty($user_dinos)) {
    echo '<p>You have not added any dinosaurs yet.</p>';
} else {
    echo generate_table($user_dinos, function ($dino) {
        $view = '<a href="view.php?id=' . $dino['id'] . '" class="btn btn-success me-1">View</a>';
        $edit = '<a href="edit.php?id=' . $dino['id'] . '" class="btn btn-primary me-1">Edit</a>';
        $delete = '<a href="delete-confirmation.php?dino=' . $dino['id'] . '&dino_name=' . urlencode($dino['genus_name'] . ' ' . $dino['species_name']) . '" class="btn btn-danger">Delete</a>';
        return $view . $edit . $delete;
    });
}
?>

<?php include 'includes/footer.php';
