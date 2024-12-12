<?php
$host = 'localhost';
$dbname = 'cms_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to get the dynamic base URL
if (!function_exists('getBaseUrl')) {

    function getBaseUrl()
    {

        $baseUrl = 'http://localhost:8080/cms_project';

        return $baseUrl;
    }
}
?>
