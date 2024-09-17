<?php
include("includes/admin_header.php");
include("includes/db.php");

// Fetching clients with their images
$clients = $conn->query("
    SELECT * FROM users WHERE role = 'client'
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


<!-- Client List Tab -->
<div class="container" id="client-list">
    <h3>Client List</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
        <thead>
            <tr>
                <th>Image</th>
                <th>Username</th>
                <th>Email</th>
                <th>Nationality</th>
                <th>Status</th>
                <th>Hobbies</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $clients->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Client Image"
                            style="width: 100px; height: auto;">
                    </td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['nationality']); ?></td>
                    <td><?php echo $row['is_married'] ? 'Married' : 'Single'; ?></td>
                    <td><?php echo htmlspecialchars($row['hobbies']); ?></td>
                    <td>
                        <a href="delete_client.php?id=<?php echo $row['id']; ?>"
                            style="color: red; text-decoration: none;">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
include("includes/footer.php");
?>