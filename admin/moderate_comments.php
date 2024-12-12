<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';


$pageID = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;


$stmt = $conn->prepare("SELECT Title FROM Pages WHERE PageID = :pageID");
$stmt->execute(['pageID' => $pageID]);
$page = $stmt->fetch();


$stmt = $conn->prepare("SELECT * FROM Comments WHERE PageID = :pageID ORDER BY CreatedAt DESC");
$stmt->execute(['pageID' => $pageID]);
$comments = $stmt->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = intval($_POST['comment_id']);
    $action = $_POST['action'];

    if ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM Comments WHERE CommentID = :id");
        $stmt->execute(['id' => $comment_id]);
    } elseif ($action === 'hide') {
        $stmt = $conn->prepare("UPDATE Comments SET IsVisible = FALSE WHERE CommentID = :id");
        $stmt->execute(['id' => $comment_id]);
    }
    
        $baseUrl = getBaseUrl();
    header("Location: {$baseUrl}/admin/moderate_comments.php?page_id=$pageID");
  
    
    exit();
}
?>
<?php include '../includes/admin_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderate Comments</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f5f5f5; }
        .btn { padding: 5px 10px; margin: 0 5px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-delete { background-color: #e74c3c; color: white; }
        .btn-hide { background-color: #f39c12; color: white; }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <h2>Moderate Comments for "<?php echo htmlspecialchars($page['Title']); ?>"</h2>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Comment</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($comment['UserName']); ?></td>
                    <td><?php echo htmlspecialchars($comment['CommentText']); ?></td>
                    <td><?php echo htmlspecialchars($comment['CreatedAt']); ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="comment_id" value="<?php echo $comment['CommentID']; ?>">
                            <button type="submit" name="action" value="delete" class="btn btn-delete">Delete</button>
                            <button type="submit" name="action" value="hide" class="btn btn-hide">Hide</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
