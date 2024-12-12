<?php
session_start();

// Generate a random CAPTCHA code
$captcha_code = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6);
$_SESSION['captcha'] = $captcha_code;

// Create an image
$captcha_image = imagecreatetruecolor(120, 40);

// Colors
$background_color = imagecolorallocate($captcha_image, 255, 255, 255);
$text_color = imagecolorallocate($captcha_image, 0, 0, 0);
$line_color = imagecolorallocate($captcha_image, 64, 64, 64);

// Fill background
imagefilledrectangle($captcha_image, 0, 0, 120, 40, $background_color);

// Add random lines for noise
for ($i = 0; $i < 5; $i++) {
    imageline($captcha_image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $line_color);
}

// Use Google Fonts CDN (Roboto)
$font_url = 'https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Me5Q.ttf';
$font_content = file_get_contents($font_url);
$temp_font = tempnam(sys_get_temp_dir(), 'font') . '.ttf';
file_put_contents($temp_font, $font_content);

// Add the CAPTCHA text
imagettftext($captcha_image, 18, rand(-10, 10), 10, 30, $text_color, $temp_font, $captcha_code);

// Output the image
header('Content-Type: image/png');
imagepng($captcha_image);
imagedestroy($captcha_image);

// Clean up temporary font file
unlink($temp_font);
