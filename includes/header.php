<?php
// if sesson not then start
if (!session_id()) {
    session_start();
}
include 'config/db.php';
// Function to get the dynamic base URL

    $baseUrl = getBaseUrl();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['UserID']);
$isAdmin = isset($_SESSION['IsAdmin']) && $_SESSION['IsAdmin'];

// Fetch categories for the dropdown menu
$stmt = $conn->query("SELECT * FROM Categories ORDER BY Name ASC");
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/styles.css">
    <style>
      
        nav a:hover {
            text-decoration: underline;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 150px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .dropdown-content a {
            color: #4CAF50;
            padding: 8px 12px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <nav>
        <div>
            <a href="<?php echo $baseUrl; ?>/index.php">Home</a>
            <?php if ($isLoggedIn): ?>
                <div class="dropdown">
                    <a href="#">Categories</a>
                    <div class="dropdown-content">
                        <!-- all -->
                        <a href="<?php echo $baseUrl; ?>/view_pages.php">All</a>
                        <?php foreach ($categories as $category): ?>
                            <a href="<?php echo $baseUrl; ?>/view_pages.php?category_id=<?php echo $category['CategoryID']; ?>">
                                <?php echo htmlspecialchars($category['Name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a href="<?php echo $baseUrl; ?>/logout.php">Logout</a>
            <?php else: ?>
                <a href="<?php echo $baseUrl; ?>/login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">
