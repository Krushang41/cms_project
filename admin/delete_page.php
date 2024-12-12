<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';

if (isset($_GET['id'])) {
    $pageID = $_GET['id'];

    
    $stmt = $conn->prepare("SELECT ImageID FROM Pages WHERE PageID = :id");
    $stmt->execute(['id' => $pageID]);
    $page = $stmt->fetch();

    if ($page['ImageID']) {
       
        $stmt = $conn->prepare("DELETE FROM Images WHERE ImageID = :id");
        $stmt->execute(['id' => $page['ImageID']]);
    }

   
    $stmt = $conn->prepare("DELETE FROM Pages WHERE PageID = :id");
    $stmt->execute(['id' => $pageID]);
}


$message = urlencode('Page was successfully deleted!');
$baseUrl = getBaseUrl();
header("Location: {$baseUrl}/admin/manage_pages.php?success_msg={$message}");
exit();

?>
