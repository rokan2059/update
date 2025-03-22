<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Generate a random string for the CAPTCHA
$captcha_text = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 6);
$_SESSION['captcha'] = $captcha_text;

// Create an image
$image = imagecreatetruecolor(120, 40);
if (!$image) {
    die("Failed to create image");
}

$background_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
imagefilledrectangle($image, 0, 0, 120, 40, $background_color);

// Add the CAPTCHA text to the image
$font_path = __DIR__ . '/arial.ttf';
if (!file_exists($font_path)) {
    die("Font file not found: $font_path");
}

imagettftext($image, 20, 0, 10, 30, $text_color, $font_path, $captcha_text);

// Output the image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>