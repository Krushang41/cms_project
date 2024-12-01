<?php
include 'config/db.php';

// Fetch all categories
$stmt = $conn->query("SELECT * FROM Categories ORDER BY Name ASC");
$categories = $stmt->fetchAll();

// Fetch pages based on selected category
$categoryID = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

if ($categoryID) {
    // Fetch pages for the selected category
    $stmt = $conn->prepare("
        SELECT Pages.*, Images.FilePath AS ImagePath 
        FROM Pages 
        LEFT JOIN Images ON Pages.ImageID = Images.ImageID
        INNER JOIN PageCategories ON Pages.PageID = PageCategories.PageID
        WHERE PageCategories.CategoryID = :category_id
    ");
    $stmt->execute(['category_id' => $categoryID]);
} else {
    // Fetch all pages if no category is selected
    $stmt = $conn->query("
        SELECT Pages.*, Images.FilePath AS ImagePath 
        FROM Pages 
        LEFT JOIN Images ON Pages.ImageID = Images.ImageID
    ");
}

$pages = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>
<h1>Available Pages</h1>

<!-- Categories Navigation -->
<nav>
    <h2>Filter by Category</h2>
    <ul>
        <li><a href="view_pages.php">All Pages</a></li>
        <?php foreach ($categories as $category): ?>
            <li>
                <a href="view_pages.php?category_id=<?php echo $category['CategoryID']; ?>">
                    <?php echo htmlspecialchars($category['Name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<!-- Pages List -->
<section>
    <?php if (count($pages) > 0): ?>
        <ul>
            <?php foreach ($pages as $page): ?>
                <li>
                    <h2><?php echo htmlspecialchars($page['Title']); ?></h2>
                    <?php if ($page['ImagePath']): ?>
                        <img src="<?php echo $page['ImagePath']; ?>" alt="Page Image" width="200">
                    <?php endif; ?>
                    <p><?php echo nl2br(htmlspecialchars($page['Content'])); ?></p>
                    <a href="view_page.php?page_id=<?php echo $page['PageID']; ?>">Read More</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No pages found for this category.</p>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
