<?php
include("includes/admin_header.php");
include("includes/db.php");

// Fetching upcoming sessions
$sessions = $conn->query("
    SELECT 
        sessions.session_date, 
        sessions.session_time, 
        users.username AS client_name, 
        users.image AS client_image,
        counselors.name AS counselor_name,
        counselors.image AS counselor_image
    FROM sessions
    JOIN users ON sessions.client_id = users.id
    JOIN counselors ON sessions.counselor_id = counselors.id
    WHERE sessions.session_date >= CURDATE()
    AND sessions.payment_status = 'paid'
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


<div class="container" id="upcoming-sessions">
    <h3>Upcoming Sessions</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
        <thead>
            <tr>
                <th>Client</th>
                <th>Counselor</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $sessions->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <img src="<?php echo htmlspecialchars($row['client_image']); ?>" alt="Client Image"
                            style="width: 80px; height: auto;">
                        <?php echo htmlspecialchars($row['client_name']); ?>
                    </td>
                    <td>
                        <img src="<?php echo htmlspecialchars($row['counselor_image']); ?>" alt="Counselor Image"
                            style="width: 80px; height: auto;">
                        <?php echo htmlspecialchars($row['counselor_name']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['session_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['session_time']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
include("includes/footer.php");
?>