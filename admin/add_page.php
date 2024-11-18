<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imageID = null;

    // Handle Image Upload
    if (!empty($_FILES['image']['tmp_name']) && isImage($_FILES['image']['tmp_name'])) {
        $uploadDir = '../uploads/';
        $fileName = time() . '_' . $_FILES['image']['name'];
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $stmt = $conn->prepare("INSERT INTO Images (FileName, FilePath) VALUES (:filename, :filepath)");
            $stmt->execute(['filename' => $fileName, 'filepath' => $filePath]);
            $imageID = $conn->lastInsertId();
        }
    }

    // Insert Page
    $stmt = $conn->prepare("INSERT INTO Pages (Title, Content, ImageID) VALUES (:title, :content, :imageID)");
    $stmt->execute(['title' => $title, 'content' => $content, 'imageID' => $imageID]);

    header("Location: manage_pages.php");
    exit();
}
?>
<?php include '../includes/admin_header.php'; ?>
<h2>Add New Page</h2>
<form method="post" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title" required>
    <label>Content</label>
    <textarea name="content" required></textarea>
    <label>Image (Optional)</label>
    <input type="file" name="image">
    <button type="submit">Add Page</button>
</form>
<?php include '../includes/admin_footer.php'; ?>
