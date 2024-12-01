<?php
include '../auth/login_check.php';
include '../auth/is_admin.php';
include '../config/db.php';
// Function to get the dynamic base URL
if (!function_exists('getBaseUrl')) {

    function getBaseUrl()
    {

        $baseUrl = 'http://localhost:8080/cms_project';

        return $baseUrl;
    }
}
$baseUrl = getBaseUrl();


// Check for sorting parameters
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'Title';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

// Validate sort_by parameter
$valid_columns = ['Title', 'CreatedAt', 'UpdatedAt'];
if (!in_array($sort_by, $valid_columns)) {
    $sort_by = 'Title'; // Default column
}

// Check if a search query is submitted
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch pages with optional search and sorting
$sql = "SELECT Pages.*, Images.FilePath AS ImagePath FROM Pages 
        LEFT JOIN Images ON Pages.ImageID = Images.ImageID";
if (!empty($search_query)) {
    $sql .= " WHERE Pages.Title LIKE :search_query";
}
$sql .= " ORDER BY $sort_by $order";

$stmt = $conn->prepare($sql);

if (!empty($search_query)) {
    $stmt->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
}

$stmt->execute();
$pages = $stmt->fetchAll();
?>

<?php include '../includes/admin_header.php'; ?>
<h2>Manage Pages</h2>
<a href="<?php echo $baseUrl; ?>/admin/add_page.php" class="btn">Add New Page</a>

<!-- Search Form -->
<div style="text-align: right; margin-bottom: 20px;">
    <form method="GET" action="" style="display: inline-block;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search Pages" style="padding: 5px;">
        <button type="submit" class="btn">Search</button>
        <a href="<?php echo $baseUrl; ?>/admin/manage_pages.php" class="btn" style="background-color: #f5f5f5; border: 1px solid #ccc; text-decoration: none;">Clear</a>
    </form>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>
                <a href="?sort_by=Title&order=<?php echo $sort_by === 'Title' && $order === 'ASC' ? 'desc' : 'asc'; ?>">
                    Title <?php echo $sort_by === 'Title' ? ($order === 'ASC' ? '▲' : '▼') : ''; ?>
                </a>
            </th>
            <th>Image</th>
            <th>
                <a href="?sort_by=CreatedAt&order=<?php echo $sort_by === 'CreatedAt' && $order === 'ASC' ? 'desc' : 'asc'; ?>">
                    Created At <?php echo $sort_by === 'CreatedAt' ? ($order === 'ASC' ? '▲' : '▼') : ''; ?>
                </a>
            </th>
            <th>
                <a href="?sort_by=UpdatedAt&order=<?php echo $sort_by === 'UpdatedAt' && $order === 'ASC' ? 'desc' : 'asc'; ?>">
                    Updated At <?php echo $sort_by === 'UpdatedAt' ? ($order === 'ASC' ? '▲' : '▼') : ''; ?>
                </a>
            </th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($pages) > 0): ?>
            <?php foreach ($pages as $page): ?>
                <tr>
                    <td><?php echo htmlspecialchars($page['Title']); ?></td>
                    <td> 
                    <?php 
if (!empty($page['ImagePath'])): 
    // Remove '../' if it exists in the path
    $sanitizedPath = str_replace('../', '', $page['ImagePath']); 
?>
    <img src="<?php echo $baseUrl . '/' . htmlspecialchars($sanitizedPath); ?>" alt="Page Image" width="50px">
<?php else: ?>
    No Image
<?php endif; ?>

                    </td>
                    <td><?php echo htmlspecialchars($page['CreatedAt']); ?></td>
                    <td><?php echo htmlspecialchars($page['UpdatedAt']); ?></td>
                    <td>
                        <a href="<?php echo $baseUrl; ?>/admin/edit_page.php?id=<?php echo $page['PageID']; ?>" class="btn">Edit</a>
                        <a href="<?php echo $baseUrl; ?>/admin/delete_page.php?id=<?php echo $page['PageID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this page?');">Delete</a>
                        <a href="<?php echo $baseUrl; ?>/admin/moderate_comments.php?page_id=<?php echo $page['PageID']; ?>" class="btn btn-info">View Comments</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No pages found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php include '../includes/admin_footer.php'; ?>
<?php
if (isset($_GET['success_msg'])) {
    $success_msg = htmlspecialchars($_GET['success_msg']);
    echo "<script>
        window.onload = function() { showSuccessToast('{$success_msg}'); }
    </script>";
}
?>