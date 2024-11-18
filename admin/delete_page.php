<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';

if (isset($_GET['id'])) {
    $pageID = $_GET['id'];

    // Get the image ID of the page
    $stmt = $conn->prepare("SELECT ImageID FROM Pages WHERE PageID = :id");
    $stmt->execute(['id' => $pageID]);
    $page = $stmt->fetch();

    if ($page['ImageID']) {
        // Delete associated image
        $stmt = $conn->prepare("DELETE FROM Images WHERE ImageID = :id");
        $stmt->execute(['id' => $page['ImageID']]);
    }

    // Delete the page
    $stmt = $conn->prepare("DELETE FROM Pages WHERE PageID = :id");
    $stmt->execute(['id' => $pageID]);
}

header('Location: manage_pages.php');
exit();
?>
