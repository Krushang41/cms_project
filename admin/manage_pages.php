<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';

$stmt = $conn->query("SELECT Pages.*, Images.FilePath AS ImagePath FROM Pages LEFT JOIN Images ON Pages.ImageID = Images.ImageID");
$pages = $stmt->fetchAll();
?>
<?php include '../includes/admin_header.php'; ?>
<h2>Manage Pages</h2>
<a href="add_page.php" class="btn">Add New Page</a>
<table class="admin-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pages as $page): ?>
            <tr>
                <td><?php echo htmlspecialchars($page['Title']); ?></td>
                <td>
                    <?php if ($page['ImagePath']): ?>
                        <img src="<?php echo $page['ImagePath']; ?>" alt="Page Image" width="50">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_page.php?id=<?php echo $page['PageID']; ?>" class="btn">Edit</a>
                    <a href="delete_page.php?id=<?php echo $page['PageID']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../includes/admin_footer.php'; ?>
