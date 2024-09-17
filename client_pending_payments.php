<?php
include 'includes/header.php';
include 'includes/redirect.php';

if ($_SESSION['role'] !== 'client') {
    // Redirect if not client
    header("Location: index.php");
    exit();
}

include 'includes/db.php';

$user_id = $_SESSION['user_id'];  // Assuming user_id is stored in session

// Fetch pending payment sessions
$pending_payment_sessions = $conn->query("
    SELECT 
        sessions.id AS session_id, 
        sessions.session_date AS session_date, 
        sessions.session_time AS session_time, 
        counselors.name AS counselor_name,
        counselors.image AS image
    FROM sessions
    JOIN counselors ON sessions.counselor_id = counselors.id
    WHERE sessions.client_id = $user_id 
    AND sessions.payment_status = 'unpaid'
    AND sessions.session_date >= CURDATE()
    ORDER BY sessions.session_date ASC
");

// Check for errors in the SQL queries
if ($conn->error) {
    echo "SQL Error: " . $conn->error;
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: auto;
        padding-top: 50px;
    }

    .card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #007bff;
        color: #fff;
        padding: 10px;
        border-radius: 8px 8px 0 0;
    }

    .card-body {
        padding: 20px;
    }

    .card-title {
        margin: 0;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    .table th {
        background-color: #f2f2f2;
    }

    .img-thumbnail {
        border-radius: 4px;
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        color: #fff;
        background-color: #007bff;
        text-decoration: none;
        text-align: center;
        font-size: 14px;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .btn-success {
        background-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .no-sessions {
        text-align: center;
        padding: 20px;
    }
</style>

<?php include "includes/client_header.php"; ?>


<main class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pending Payments</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Counselor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($pending_payment_sessions->num_rows > 0): ?>
                        <?php while ($row = $pending_payment_sessions->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['session_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['session_time']); ?></td>
                                <td>
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Counselor Image"
                                        class="img-thumbnail" style="width: 80px; height: auto;">

                                    <?php echo htmlspecialchars($row['counselor_name']); ?>
                                </td>
                                <td>
                                    <a href="payment.php?session_id=<?php echo $row['session_id']; ?>" class="btn btn-success">
                                        Pay Now
                                    </a>

                                    <a href="edit_booking.php?session_id=<?php echo $row['session_id']; ?>"
                                        class="btn btn-warning">
                                        Edit
                                    </a>
                                    <a href="delete_booking.php?session_id=<?php echo $row['session_id']; ?>"
                                        class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this session?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="no-sessions">No pending payments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>