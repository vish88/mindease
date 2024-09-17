<?php
include 'includes/header.php';
include 'includes/redirect.php';

if ($_SESSION['role'] !== 'client') {
    header("Location: index.php");
    exit();
}

include 'includes/db.php';

$session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : 0;

// Ensure the session belongs to the current user
$check_query = $conn->prepare("
    SELECT id 
    FROM sessions 
    WHERE id = ? 
    AND client_id = ?
");
$check_query->bind_param('ii', $session_id, $_SESSION['user_id']);
$check_query->execute();
$check_result = $check_query->get_result();

if ($check_result->num_rows === 0) {
    // Invalid session, redirect with error message
    $_SESSION['message'] = 'Invalid session';
    header("Location: client_dashboard.php");
    exit();
}

// Perform deletion
$delete_query = $conn->prepare("DELETE FROM sessions WHERE id = ?");
$delete_query->bind_param('i', $session_id);

if ($delete_query->execute()) {
    // Successful deletion, redirect with success message
    $_SESSION['message'] = 'Session deleted successfully';
    header("Location: client_dashboard.php");

} else {
    // Error deleting session, redirect with error message
    $_SESSION['message'] = 'Error deleting session: ' . $conn->error;
    header("Location: client_dashboard.php");

}

include "includes/footer.php";
exit();
?>