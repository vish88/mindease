<?php
include("includes/admin_header.php");
include("includes/db.php");

// Fetching completed sessions
$completed_sessions = $conn->query("
    SELECT 
        sessions.id AS session_id,
        sessions.session_date AS session_date, 
        sessions.session_time AS session_time, 
        users.username AS client_name, 
        users.image AS uimage,
        counselors.name AS counselor_name,
        counselors.image AS cimage,
        sessions.notes AS notes
    FROM sessions
    JOIN users ON sessions.client_id = users.id
    JOIN counselors ON sessions.counselor_id = counselors.id
    WHERE sessions.session_date < CURDATE()
    ORDER BY sessions.session_date ASC
");
?>

<style>
    .container {
        width: 80%;
        margin: auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
</style>


<!-- Completed Sessions -->
<div class="container" id="completed-sessions">
    <h3>Completed Sessions</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
        <thead>
            <tr>
                <th>Client</th>
                <th>Counselor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $completed_sessions->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <img src="<?php echo htmlspecialchars($row['uimage']); ?>" alt="Client Image"
                            style="width: 80px; height: auto; vertical-align: middle;">
                        <?php echo htmlspecialchars($row['client_name']); ?>
                    </td>
                    <td>
                        <img src="<?php echo htmlspecialchars($row['cimage']); ?>" alt="Counselor Image"
                            style="width: 80px; height: auto; vertical-align: middle;">
                        <?php echo htmlspecialchars($row['counselor_name']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['session_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['session_time']); ?></td>
                    <td>
                        <a href="edit_session.php?id=<?php echo $row['session_id']; ?>"
                            style="color: orange; text-decoration: none;">Edit Notes</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
include("includes/footer.php");
?>