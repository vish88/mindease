<?php
include "includes/header.php";
include 'includes/db.php';

$id = $_GET['id'];

// Check if the client has any associated sessions
$stmt = $conn->prepare("SELECT COUNT(*) FROM sessions WHERE client_id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_row()[0];

if ($count > 0) {
    // Sessions exist, prompt for confirmation
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['confirm_delete'])) {
            // Proceed with deletion
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                // Also delete associated sessions
                $stmt = $conn->prepare("DELETE FROM sessions WHERE client_id = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();

                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "<p class='alert alert-danger'>Error executing query: " . htmlspecialchars($stmt->error) . "</p>";
            }
            $stmt->close();
        } elseif (isset($_POST['cancel_delete'])) {
            // Redirect back to admin dashboard without deleting
            header("Location: admin_dashboard.php");
            exit();
        }
    }
    ?>

    <header>
        <title>Confirm Deletion</title>
        <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Update the path to your Bootstrap CSS file -->
    </header>

    <div class="container mt-5">
        <div class="alert alert-warning">
            <strong>Warning!</strong> This client has associated sessions. Deleting the client will also remove all
            associated sessions.
        </div>
        <form action="delete_client.php?id=<?php echo htmlspecialchars($id); ?>" method="post">
            <button type="submit" name="confirm_delete" class="btn btn-danger">Confirm Delete</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <?php
} else {
    // No sessions, proceed with deletion
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<p class='alert alert-danger'>Error executing query: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}
$conn->close();

include "includes/footer.php";
?>