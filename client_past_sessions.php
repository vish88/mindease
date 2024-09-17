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

// Fetch past sessions
$past_sessions = $conn->query("
    SELECT 
        sessions.session_date AS session_date, 
        sessions.session_time AS session_time, 
        sessions.notes AS notes, 
        counselors.name AS counselor_name,
        counselors.image AS image
    FROM sessions
    JOIN counselors ON sessions.counselor_id = counselors.id
    WHERE sessions.client_id = $user_id 
    AND sessions.session_date < CURDATE()
    ORDER BY sessions.session_date DESC
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

    .no-sessions {
        text-align: center;
        padding: 20px;
    }
</style>

<?php include "includes/client_header.php"; ?>


<main class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Past Sessions</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Counselor</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($past_sessions->num_rows > 0): ?>
                        <?php while ($row = $past_sessions->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['session_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['session_time']); ?></td>
                                <td>
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Counselor Image"
                                        class="img-thumbnail" style="width: 80px; height: auto;">

                                    <?php echo htmlspecialchars($row['counselor_name']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['notes']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="no-sessions">No past sessions found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>