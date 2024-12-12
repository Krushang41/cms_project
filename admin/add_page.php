<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imageID = null;

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

    $stmt = $conn->prepare("INSERT INTO Pages (Title, Content, ImageID) VALUES (:title, :content, :imageID)");
    $stmt->execute(['title' => $title, 'content' => $content, 'imageID' => $imageID]);

$message = urlencode("Page '{$title}' was successfully added!");

$baseUrl = getBaseUrl();
header("Location: {$baseUrl}/admin/manage_pages.php?success_msg={$message}");


exit();


}
?>
<?php include '../includes/admin_header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Page</title>
  
    
    <script src="https://cdn.tiny.cloud/1/swteaszeemlfhlo7zl8otan8ww8ur1vjttvu236duxr9s88j/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea[name="content"]',
            plugins: 'link image code lists table',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
            height: 300,
        });
    </script>
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
        .form-container input[type="file"] {
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
</head>
<body>
    <div class="form-container">
        <h2>Add New Page</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required placeholder="Enter the title of the page">

            <label for="content">Content</label>
            <textarea id="content" name="content" placeholder="Enter the content of the page"></textarea>

            <label for="image">Image (Optional)</label>
            <input type="file" id="image" name="image">

            <button type="submit">Add Page</button>
        </form>
    </div>
</body>
<script>tinymce.init({
    selector: 'textarea[name="content"]',
    plugins: 'link image code lists table',
    toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
    height: 300,
    setup: function (editor) {
        editor.on('submit', function (e) {
            if (!editor.getContent().trim()) {
                alert('Content cannot be empty.');
                e.preventDefault();
            }
        });
    },
});
</script>
</html>

