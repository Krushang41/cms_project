<?php
include 'config/db.php';
include 'includes/header.php';

// Get search query and pagination inputs
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Ensure page is at least 1
$results_per_page = 5; // Number of results per page
$offset = ($page - 1) * $results_per_page;

// Fetch categories for the dropdown
$stmt = $conn->query("SELECT * FROM Categories ORDER BY Name ASC");
$categories = $stmt->fetchAll();
// Build the main query
$sql = "
    SELECT 
        Pages.PageID, 
        Pages.Title, 
        Pages.Content, 
        Images.FilePath AS ImagePath, 
        Categories.Name AS CategoryName
    FROM Pages
    LEFT JOIN Images ON Pages.ImageID = Images.ImageID
    LEFT JOIN PageCategories ON Pages.PageID = PageCategories.PageID
    LEFT JOIN Categories ON PageCategories.CategoryID = Categories.CategoryID
    WHERE Pages.Title LIKE :query
";

$params = [':query' => '%' . $search_query . '%'];

if ($category_id) {
    $sql .= " AND Categories.CategoryID = :category_id";
    $params[':category_id'] = $category_id;
}

// Add pagination to the query
$sql .= " LIMIT :offset, :results_per_page";
$params[':offset'] = $offset;
$params[':results_per_page'] = $results_per_page;

// Execute the query
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();

// Count total results for pagination
$count_sql = "
    SELECT COUNT(DISTINCT Pages.PageID) AS total
    FROM Pages
    LEFT JOIN PageCategories ON Pages.PageID = PageCategories.PageID
    LEFT JOIN Categories ON PageCategories.CategoryID = Categories.CategoryID
    WHERE Pages.Title LIKE :query
";
if ($category_id) {
    $count_sql .= " AND Categories.CategoryID = :category_id";
}
$count_stmt = $conn->prepare($count_sql);
$count_stmt->execute($params);
$total_results = $count_stmt->fetch()['total'];
$total_pages = ceil($total_results / $results_per_page);
?>

<div class="container">
    <h1>Search Pages</h1>
    
    <!-- Search Form -->
    <form method="GET" action="search.php" class="search-form">
        <input type="text" name="query" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>" required>
        <select name="category_id">
            <option value="0">All Categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['CategoryID']; ?>" <?php echo $category_id == $category['CategoryID'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['Name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Search</button>
    </form>

    <!-- Results -->
    <?php if (!empty($results)): ?>
        <ul class="search-results">
            <?php foreach ($results as $result): ?>
                <li>
                    <h2><a href="view_page.php?page_id=<?php echo $result['PageID']; ?>"><?php echo htmlspecialchars($result['Title']); ?></a></h2>
                    <p><?php echo nl2br(htmlspecialchars(substr($result['Content'], 0, 150))); ?>...</p>
                    <small>Category: <?php echo htmlspecialchars($result['CategoryName'] ?: 'None'); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?query=<?php echo urlencode($search_query); ?>&category_id=<?php echo $category_id; ?>&page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?query=<?php echo urlencode($search_query); ?>&category_id=<?php echo $category_id; ?>&page=<?php echo $i; ?>" <?php echo $i == $page ? 'class="active"' : ''; ?>>
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?query=<?php echo urlencode($search_query); ?>&category_id=<?php echo $category_id; ?>&page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
