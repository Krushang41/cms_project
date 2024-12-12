<?php
include 'config/db.php';

$results_per_page = 2; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $results_per_page; 


$stmt = $conn->query("SELECT * FROM Categories ORDER BY Name ASC");
$categories = $stmt->fetchAll();


$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category_id = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? intval($_GET['category_id']) : null;


$sql = "
    SELECT Pages.*, Images.FilePath AS ImagePath 
    FROM Pages
    LEFT JOIN Images ON Pages.ImageID = Images.ImageID
    LEFT JOIN PageCategories ON Pages.PageID = PageCategories.PageID
    LEFT JOIN Categories ON PageCategories.CategoryID = Categories.CategoryID
    WHERE 1
";

$params = [];


if (!empty($search_query)) {
    $sql .= " AND Pages.Title LIKE :query";
    $params[':query'] = '%' . $search_query . '%';
}


if ($category_id) {
    $sql .= " AND Categories.CategoryID = :category_id";
    $params[':category_id'] = $category_id;
}


$sql .= " LIMIT :offset, :results_per_page";
$params[':offset'] = $offset;
$params[':results_per_page'] = $results_per_page;


$stmt = $conn->prepare($sql);


foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}

$stmt->execute();
$pages = $stmt->fetchAll();


$count_sql = "
    SELECT COUNT(DISTINCT Pages.PageID) AS total
    FROM Pages
    LEFT JOIN PageCategories ON Pages.PageID = PageCategories.PageID
    LEFT JOIN Categories ON PageCategories.CategoryID = Categories.CategoryID
    WHERE 1
";
if (!empty($search_query)) {
    $count_sql .= " AND Pages.Title LIKE :query";
}
if ($category_id) {
    $count_sql .= " AND Categories.CategoryID = :category_id";
}
$count_stmt = $conn->prepare($count_sql);


foreach ($params as $key => $value) {
    if ($key !== ':offset' && $key !== ':results_per_page') {
        $count_stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
}
$count_stmt->execute();
$total_results = $count_stmt->fetch()['total'];
$total_pages = ceil($total_results / $results_per_page);
?>

<?php include 'includes/header.php'; ?>


<section class="page-list">
    <?php if (count($pages) > 0): ?>
        <div class="card-container">
            <?php foreach ($pages as $page): ?>
                <div class="card">
                <?php 
if (!empty($page['ImagePath'])): 
    +
    $sanitizedPath = str_replace('../', '', $page['ImagePath']); 
?>
    <img src="<?php echo $baseUrl . '/' . htmlspecialchars($sanitizedPath); ?>" alt="Page Image" class="card-img">
<?php else: ?>
    No Image
<?php endif; ?>
                    <div class="card-content">
                        <h2 class="card-title"><?php echo htmlspecialchars($page['Title']); ?></h2>
                        <a href="view_page.php?page_id=<?php echo $page['PageID']; ?>" class="card-btn">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No pages found.</p>
    <?php endif; ?>
</section>

<?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?query=<?php echo urlencode($search_query); ?>&category_id=<?php echo $category_id; ?>&page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?query=<?php echo urlencode($search_query); ?>&category_id=<?php echo $category_id; ?>&page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?query=<?php echo urlencode($search_query); ?>&category_id=<?php echo $category_id; ?>&page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
