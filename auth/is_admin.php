<?php
if ($_SESSION['IsAdmin'] != 1) {
    header("Location: ../index.php");
    exit();
}
?>
