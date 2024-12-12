<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';
include '../includes/functions.php';

$pageID = $_GET['id'];


$stmt = $conn->prepare("SELECT * FROM Pages WHERE PageID = :id");
$stmt->execute(['id' => $pageID]);
$page = $stmt->fetch();


$stmt = $conn->prepare("SELECT * FROM Categories ORDER BY Name ASC");
$stmt->execute();
$categories = $stmt->fetchAll();


$stmt = $conn->prepare("SELECT CategoryID FROM PageCategories WHERE PageID = :pageID");
$stmt->execute(['pageID' => $pageID]);
$assignedCategories = $stmt->fetchAll(PDO::FETCH_COLUMN);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imageID = $page['ImageID'];
    $selectedCategories = isset($_POST['categories']) ? $_POST['categories'] : [];

  
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


    if (isset($_POST['delete_image']) && $page['ImageID']) {
        $stmt = $conn->prepare("DELETE FROM Images WHERE ImageID = :id");
        $stmt->execute(['id' => $page['ImageID']]);
        $imageID = null;
    }

    $stmt = $conn->prepare("UPDATE Pages SET Title = :title, Content = :content, ImageID = :imageID WHERE PageID = :id");
    $stmt->execute(['title' => $title, 'content' => $content, 'imageID' => $imageID, 'id' => $pageID]);

 
    $stmt = $conn->prepare("DELETE FROM PageCategories WHERE PageID = :pageID");
    $stmt->execute(['pageID' => $pageID]);


    foreach ($selectedCategories as $categoryID) {
        $stmt = $conn->prepare("INSERT INTO PageCategories (PageID, CategoryID) VALUES (:pageID, :categoryID)");
        $stmt->execute(['pageID' => $pageID, 'categoryID' => $categoryID]);
    }


    $message = urlencode("Page '{$title}' was successfully updated!");

    $baseUrl = getBaseUrl();
header("Location: {$baseUrl}/admin/manage_pages.php?success_msg={$message}");
    exit();
}
?>
<?php include '../includes/admin_header.php'; ?>
<style>
    .form-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
        text-align: center;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
    }

    .form-container label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        font-family: Arial, sans-serif;
    }

    .form-container input[type="text"],
    .form-container textarea,
    .form-container input[type="file"],
    .form-container select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-family: Arial, sans-serif;
    }

    .form-container textarea {
        height: 150px;
        resize: vertical;
    }

    .form-container button {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-family: Arial, sans-serif;
        cursor: pointer;
    }

    .form-container button:hover {
        background-color: #45a049;
    }
</style>

<div class="form-container">
    <h2>Edit Page</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($page['Title']); ?>" required>

        <label for="content">Content</label>
        <textarea id="content" name="content" required><?php echo htmlspecialchars($page['Content']); ?></textarea>

        <label for="image">Image (Optional)</label>
        <input type="file" id="image" name="image">

        <?php if ($page['ImageID']): ?>
            <div class="current-image">
                <p>Current Image: <a href="../uploads/<?php echo $page['ImageID']; ?>" target="_blank">View</a></p>
                <label class="delete-image">
                    <input type="checkbox" name="delete_image"> Delete Current Image
                </label>
            </div>
        <?php endif; ?>

        <label for="categories">Categories</label>
        <select id="categories" name="categories[]" multiple>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['CategoryID']; ?>" <?php echo in_array($category['CategoryID'], $assignedCategories) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['Name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Update Page</button>
    </form>
</div>

<?php include '../includes/admin_footer.php'; ?>


<script src="https://cdn.tiny.cloud/1/swteaszeemlfhlo7zl8otan8ww8ur1vjttvu236duxr9s88j/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea[name="content"]',
        plugins: 'link image code lists table',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
        height: 300,
    });
</script>
