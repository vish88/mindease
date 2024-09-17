<?php
include 'includes/db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM counselors WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

header("Location: admin_dashboard.php");
?>