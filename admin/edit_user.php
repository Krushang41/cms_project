<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';


$userID = isset($_GET['id']) ? intval($_GET['id']) : 0;


$stmt = $conn->prepare("SELECT * FROM Users WHERE UserID = :id");
$stmt->execute(['id' => $userID]);
$user = $stmt->fetch();

if (!$user) {
   
    header('Location: manage_users.php');
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

  
    if (!empty($_POST['password'])) {
        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE Users SET Username = :username, PasswordHash = :password, IsAdmin = :is_admin WHERE UserID = :id");
        $stmt->execute(['username' => $username, 'password' => $password, 'is_admin' => $isAdmin, 'id' => $userID]);
    } else {
        $stmt = $conn->prepare("UPDATE Users SET Username = :username, IsAdmin = :is_admin WHERE UserID = :id");
        $stmt->execute(['username' => $username, 'is_admin' => $isAdmin, 'id' => $userID]);
    }    
    

    $message = "User updated successfully!";
    
    $baseUrl = getBaseUrl();
    header("Location: {$baseUrl}/admin/manage_users.php?success_msg={$message}");

    exit();
}
?>
<?php include '../includes/admin_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        .form-container {
            max-width: 400px;
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
        .form-container input[type="password"],
        .form-container input[type="checkbox"] {
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

        .message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit User</h2>
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" required>

        <label for="password">Password (Leave blank to keep current)</label>
        <input type="password" id="password" name="password">

        <label for="is_admin">
            <input type="checkbox" id="is_admin" name="is_admin" <?php echo $user['IsAdmin'] ? 'checked' : ''; ?>> Is Admin
        </label>

        <button type="submit">Update User</button>
    </form>
</div>
</body>
</html>
