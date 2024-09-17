<?php
include 'includes/header.php';
include 'includes/redirect.php';

if ($_SESSION['role'] !== 'client') {
    header("Location: index.php");
    exit();
}

include 'includes/db.php';

$session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : 0;

// Fetch session details
$session_query = $conn->query(
    "
    SELECT 
        sessions.session_date AS session_date, 
        sessions.session_time AS session_time, 
        counselors.name AS counselor_name
    FROM sessions
    JOIN counselors ON sessions.counselor_id = counselors.id
    WHERE sessions.id = $session_id
    AND sessions.client_id = " . $_SESSION['user_id']
);

$session = $session_query->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $new_date = $_POST['session_date'];
    $new_time = $_POST['session_time'];

    $update_query = $conn->prepare("
        UPDATE sessions 
        SET session_date = ?, session_time = ? 
        WHERE id = ?
    ");
    $update_query->bind_param('ssi', $new_date, $new_time, $session_id);

    if ($update_query->execute()) {
        echo "<script>alert('Session updated successfully'); window.location.href='client_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating session');</script>";
    }
}
?>

<main class="container mt-5">
    <h2>Edit Session</h2>

    <form method="POST">
        <div class="form-group">
            <label for="session_date">Date</label>
            <input type="date" id="session_date" name="session_date" class="form-control"
                value="<?php echo htmlspecialchars($session['session_date']); ?>" required>
        </div>
        <div class="form-group">
            <label for="session_time">Time</label>
            <input type="time" id="session_time" name="session_time" class="form-control"
                value="<?php echo htmlspecialchars($session['session_time']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Session</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>