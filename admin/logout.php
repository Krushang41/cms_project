<?php
session_start();
session_destroy();
$message = urlencode('You have been logged out.');
$baseUrl = getBaseUrl();
header("Location: {$baseUrl}/admin/login.php?success_msg={$message}");
exit();
?>
