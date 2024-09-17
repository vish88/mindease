<?php
include("includes/admin_header.php");
include("includes/db.php");

$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
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


<div class="container">
    <h3>Messages</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td> <?php echo htmlspecialchars($row["first_name"]); ?> </td>
                    <td> <?php echo htmlspecialchars($row["last_name"]); ?> </td>
                    <td> <?php echo htmlspecialchars($row["email"]); ?> </td>
                    <td> <?php echo htmlspecialchars($row["message"]); ?> </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<?php
include 'includes/footer.php';
?>