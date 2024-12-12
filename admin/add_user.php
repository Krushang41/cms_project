<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';

$message = "";



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $isAdmin = isset($_POST['is_admin']) ? 1 : 0;


    
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->rowCount() > 0) {
        $message = "Username already exists!";
    } else {
      
        
        $stmt = $conn->prepare("INSERT INTO Users (Username, PasswordHash, IsAdmin) VALUES (:username, :password, :is_admin)");
        $stmt->execute(['username' => $username, 'password' => $password, 'is_admin' => $isAdmin]);
        $message = "User added successfully!";
    }
}
?>

<?php include '../includes/admin_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
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
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Add User</h2>
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="is_admin">
            <input type="checkbox" id="is_admin" name="is_admin"> Is Admin
        </label>

        <button type="submit">Add User</button>
    </form>
</div>
</body>
</html>
