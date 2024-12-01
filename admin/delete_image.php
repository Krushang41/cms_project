<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';

if (isset($_GET['id'])) {
    $imageID = $_GET['id'];

    // Get the image file path
    $stmt = $conn->prepare("SELECT FilePath FROM Images WHERE ImageID = :id");
    $stmt->execute(['id' => $imageID]);
    $image = $stmt->fetch();

    if ($image) {
        // Delete the file from the server
        unlink($image['FilePath']);

        // Delete the record from the database
        $stmt = $conn->prepare("DELETE FROM Images WHERE ImageID = :id");
        $stmt->execute(['id' => $imageID]);
    }
}
$baseUrl = getBaseUrl();
header("Location: {$baseUrl}/admin/manage_pages.php");
exit();

?>
