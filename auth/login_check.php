<?php
session_start();
if (!isset($_SESSION['UserID'])) {
    header("Location: ../admin/login.php");
    exit();
}
?>
