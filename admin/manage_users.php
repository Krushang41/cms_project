<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';

// Handle user deletion
if (isset($_GET['delete'])) {
    $userID = $_GET['delete'];

    // Prevent self-deletion
    if ($userID != $_SESSION['UserID']) {
        $stmt = $conn->prepare("DELETE FROM Users WHERE UserID = :id");
        $stmt->execute(['id' => $userID]);
    }
    header('Location: manage_users.php');
    exit();
}

// Fetch all users
$stmt = $conn->query("SELECT * FROM Users");
$users = $stmt->fetchAll();
?>
<?php include '../includes/admin_header.php'; ?>
<h1>Manage Users</h1>
<a href="add_user.php">Add New User</a>
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Admin</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['Username']); ?></td>
                <td><?php echo $user['IsAdmin'] ? 'Yes' : 'No'; ?></td>
                <td>
                    <?php if ($user['UserID'] != $_SESSION['UserID']): ?>
                        <a href="edit_user.php?id=<?php echo $user['UserID']; ?>">Edit</a>
                        <a href="manage_users.php?delete=<?php echo $user['UserID']; ?>">Delete</a>
                    <?php else: ?>
                        <em>Your account</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>
