<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';
include '../includes/functions.php';

$pageID = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Pages WHERE PageID = :id");
$stmt->execute(['id' => $pageID]);
$page = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imageID = $page['ImageID'];

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

    // Handle Image Deletion
    if (isset($_POST['delete_image']) && $page['ImageID']) {
        $stmt = $conn->prepare("DELETE FROM Images WHERE ImageID = :id");
        $stmt->execute(['id' => $page['ImageID']]);
        $imageID = null;
    }

    // Update Page
    $stmt = $conn->prepare("UPDATE Pages SET Title = :title, Content = :content, ImageID = :imageID WHERE PageID = :id");
    $stmt->execute(['title' => $title, 'content' => $content, 'imageID' => $imageID, 'id' => $pageID]);

    header("Location: manage_pages.php");
    exit();
}
?>
<?php include '../includes/admin_header.php'; ?>
<h2>Edit Page</h2>
<form method="post" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($page['Title']); ?>" required>
    <label>Content</label>
    <textarea name="content" required><?php echo htmlspecialchars($page['Content']); ?></textarea>
    <label>Image (Optional)</label>
    <input type="file" name="image">
    <?php if ($page['ImageID']): ?>
        <p>Current Image: <a href="../uploads/<?php echo $page['ImageID']; ?>" target="_blank">View</a></p>
        <label>
            <input type="checkbox" name="delete_image"> Delete Current Image
        </label>
    <?php endif; ?>
    <button type="submit">Update Page</button>
</form>
<?php include '../includes/admin_footer.php'; ?>
