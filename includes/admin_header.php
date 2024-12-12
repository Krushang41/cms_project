<?php
// db.php includes the database connection and the getBaseUrl function
include '../config/db.php';

$baseUrl = getBaseUrl();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/admin_styles.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body>
    <header class="admin-header">
        <h1>Admin Panel</h1>
    </header>
    <?php include 'admin_menu.php'; ?>
    <main class="admin-content">
