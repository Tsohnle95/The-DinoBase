<?php

$message = $message ?? "";
$file_name_new = $file_name_new ?? "";

if (isset($_FILES['img-file']) && $_FILES['img-file']['error'] !== 4) {

    $file_name = $_FILES['img-file']['name'];
    $file_temp_name = $_FILES['img-file']['tmp_name'];
    $file_size = $_FILES['img-file']['size'];

    if ($_FILES['img-file']['error'] === 0) {

        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed = array('avif', 'jpg', 'jpeg', 'png', 'webp');

        if (in_array($file_extension, $allowed)) {

            if ($file_size < 2000000) {

                $file_name_new = uniqid('', TRUE) . ".$file_extension";


                $full_dir = __DIR__ . '/../../data/img/full/';
                $thumbs_dir = __DIR__ . '/../../data/img/thumbs/';

                if (!is_dir($full_dir)) {
                    mkdir($full_dir, 0777, TRUE);
                }
                if (!is_dir($thumbs_dir)) {
                    mkdir($thumbs_dir, 0777, TRUE);
                }

                $file_destination = $full_dir . $file_name_new;

                move_uploaded_file($file_temp_name, $file_destination);

                switch ($file_extension) {
                    case 'avif':
                        $src_image = imagecreatefromavif($file_destination);
                        break;
                    case 'jpg':
                    case 'jpeg':
                        $src_image = imagecreatefromjpeg($file_destination);
                        break;
                    case 'png':
                        $src_image = imagecreatefrompng($file_destination);
                        break;
                    case 'webp':
                        $src_image = imagecreatefromwebp($file_destination);
                        break;
                    default:
                        exit("Unsupported file type. Please upload a AVIF, JPG, JPEG, PNG, or WebP file.");
                }

                list($width_orig, $height_orig) = getimagesize($file_destination);

                if ($width_orig > $height_orig) {
                    $target_width = 1280;
                    $target_height = 720;
                } elseif ($height_orig > $width_orig) {
                    $target_width = 720;
                    $target_height = 1280;
                } else {
                    $target_width = 720;
                    $target_height = 720;
                }

                $scale_x = $target_width / $width_orig;
                $scale_y = $target_height / $height_orig;
                $scale = max($scale_x, $scale_y);

                $new_width = (int) ceil($width_orig * $scale);
                $new_height = (int) ceil($height_orig * $scale);

                $temp_image = imagecreatetruecolor($new_width, $new_height);

                imagecopyresampled($temp_image, $src_image, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);

                $x_offset = (int) round(($new_width - $target_width) / 2);
                $y_offset = (int) round(($new_height - $target_height) / 2);

                $final_image = imagecreatetruecolor($target_width, $target_height);

                imagecopy($final_image, $temp_image, 0, 0, $x_offset, $y_offset, $target_width, $target_height);

                switch ($file_extension) {
                    case 'avif':
                        imageavif($final_image, $file_destination);
                        break;
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($final_image, $file_destination);
                        break;
                    case 'png':
                        imagepng($final_image, $file_destination);
                        break;
                    case 'webp':
                        imagewebp($final_image, $file_destination);
                        break;
                    default:
                        exit("Unsupported file type. Please upload a JPG, JPEG, PNG, WebP or AVIF file.");
                }

                $thumb_size = 512;
                $thumb_img = imagecreatetruecolor($thumb_size, $thumb_size);
                $smaller_side = min($width_orig, $height_orig);
                $src_x = (int) round(($width_orig - $smaller_side) / 2);
                $src_y = (int) round(($height_orig - $smaller_side) / 2);
                imagecopyresampled($thumb_img, $src_image, 0, 0, $src_x, $src_y, $thumb_size, $thumb_size, $smaller_side, $smaller_side);

                $thumb_path = $thumbs_dir . $file_name_new;

                switch ($file_extension) {
                    case 'avif':
                        imageavif($thumb_img, $thumb_path);
                        break;
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($thumb_img, $thumb_path, 100);
                        break;
                    case 'png':
                        imagepng($thumb_img, $thumb_path);
                        break;
                    case 'webp':
                        imagewebp($thumb_img, $thumb_path);
                        break;
                    default:
                        exit("Unsupported file type. Please upload a JPG, JPEG, PNG, WebP or AVIF file.");
                }

                imagedestroy($src_image);
                imagedestroy($temp_image);
                imagedestroy($final_image);
                imagedestroy($thumb_img);

                $message = "Your image was successfully uploaded!";

            } else {
                $message .= "The file size limit is 2MB. Please upload a smaller file.";
            }

        } else {
            $message .= "Your image must be one of the following file types: AVIF, JPG, JPEG, PNG, or WebP.";
        }

    } else {
        $message .= "There was an error with your file. Please make sure it isn't corrupted and try uploading again later.";
    }

}

?>