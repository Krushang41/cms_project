<?php
session_start();

$captcha_code = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6);
$_SESSION['captcha'] = $captcha_code;

$captcha_image = imagecreatetruecolor(120, 40);

$background_color = imagecolorallocate($captcha_image, 255, 255, 255);
$text_color = imagecolorallocate($captcha_image, 0, 0, 0);
$line_color = imagecolorallocate($captcha_image, 64, 64, 64);

imagefilledrectangle($captcha_image, 0, 0, 120, 40, $background_color);

for ($i = 0; $i < 5; $i++) {
    imageline($captcha_image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $line_color);
}

$font_url = 'https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Me5Q.ttf';
$font_content = file_get_contents($font_url);
$temp_font = tempnam(sys_get_temp_dir(), 'font') . '.ttf';
file_put_contents($temp_font, $font_content);


imagettftext($captcha_image, 18, rand(-10, 10), 10, 30, $text_color, $temp_font, $captcha_code);

header('Content-Type: image/png');
imagepng($captcha_image);
imagedestroy($captcha_image);


unlink($temp_font);
