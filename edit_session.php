<?php
include 'includes/header.php';
include 'includes/db.php';

$session_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch session details
$stmt = $conn->prepare("SELECT * FROM sessions WHERE id = ?");
$stmt->bind_param('i', $session_id);
$stmt->execute();
$result = $stmt->get_result();
$session = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notes = $_POST['notes'];

    // Update notes in the session
    $stmt = $conn->prepare("UPDATE sessions SET notes = ? WHERE id = ?");
    $stmt->bind_param('si', $notes, $session_id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "Unable to update notes, please try again.";
    }
}
?>

<main class="container mt-5">
    <h2 class="mb-4">Edit Session Notes</h2>

    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php } ?>

    <form action="edit_session.php?id=<?php echo $session_id; ?>" method="post">
        <div class="form-group">
            <label for="notes">Session Notes:</label>
            <textarea id="notes" name="notes" class="form-control"
                rows="10"><?php echo htmlspecialchars($session['notes']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary mb-4">Save Notes</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</main>

<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#notes'))
        .catch(error => {
            console.error(error);
        });
</script>

<?php include 'includes/footer.php'; ?>