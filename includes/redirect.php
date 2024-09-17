<?php
if (!isset($_SESSION['email'])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}

?>