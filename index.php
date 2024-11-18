<?php
include 'config/db.php';

$stmt = $conn->query("SELECT Pages.*, Images.FilePath AS ImagePath FROM Pages LEFT JOIN Images ON Pages.ImageID = Images.ImageID");
$pages = $stmt->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<h1>Pages</h1>
<ul>
    <?php foreach ($pages as $page): ?>
        <li>
            <h2><?php echo htmlspecialchars($page['Title']); ?></h2>
            <?php if ($page['ImagePath']): ?>
                <img src="<?php echo $page['ImagePath']; ?>" alt="Page Image" width="200">
            <?php endif; ?>
            <p><?php echo nl2br(htmlspecialchars($page['Content'])); ?></p>
        </li>
    <?php endforeach; ?>
</ul>
<?php include 'includes/footer.php'; ?>
