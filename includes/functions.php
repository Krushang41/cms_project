<?php
function isImage($filePath) {
    $imageType = exif_imagetype($filePath);
    return in_array($imageType, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF]);
}

function resizeImage($source, $destination, $maxWidth, $maxHeight) {
    $info = getimagesize($source);
    list($width, $height) = $info;

    $ratio = min($maxWidth / $width, $maxHeight / $height);
    $newWidth = $width * $ratio;
    $newHeight = $height * $ratio;

    $image = null;
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    }

    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if ($info['mime'] == 'image/jpeg') {
        imagejpeg($newImage, $destination, 75);
    } elseif ($info['mime'] == 'image/png') {
        imagepng($newImage, $destination, 8);
    } elseif ($info['mime'] == 'image/gif') {
        imagegif($newImage, $destination);
    }

    return true;
}

function logAction($userID, $action) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Logs (UserID, Action) VALUES (:userID, :action)");
    $stmt->execute(['userID' => $userID, 'action' => $action]);
}
?>
