<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';

$message = "";

// Handle form submission for adding/updating categories
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;

    if (!empty($category_name)) {
        if ($category_id) {
            // Update the existing category
            $stmt = $conn->prepare("UPDATE Categories SET Name = :name WHERE CategoryID = :id");
            $stmt->execute(['name' => $category_name, 'id' => $category_id]);
            $message = "Category updated successfully!";
        } else {
            // Insert a new category
            $stmt = $conn->prepare("INSERT INTO Categories (Name) VALUES (:name)");
            $stmt->execute(['name' => $category_name]);
            $message = "Category added successfully!";
        }
    }
}

// Fetch all categories for display
$stmt = $conn->prepare("SELECT * FROM Categories ORDER BY Name ASC");
$stmt->execute();
$categories = $stmt->fetchAll();
?>
<?php include '../includes/admin_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
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

        .form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: Arial, sans-serif;
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

        .table-container {
            max-width: 800px;
            margin: 50px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f5f5f5;
        }

        .btn-edit {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Manage Categories</h2>
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="category_id" id="category_id">
            <label for="category_name">Category Name</label>
            <input type="text" name="category_name" id="category_name" required placeholder="Enter category name">
            <button type="submit">Save Category</button>
        </form>
    </div>

    <div class="table-container">
        <h3>Existing Categories</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['Name']); ?></td>
                        <td>
                            <button class="btn-edit" onclick="editCategory('<?php echo $category['CategoryID']; ?>', '<?php echo htmlspecialchars($category['Name']); ?>')">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="2">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editCategory(id, name) {
            document.getElementById('category_id').value = id;
            document.getElementById('category_name').value = name;
        }
    </script>
</body>
</html>
