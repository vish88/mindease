<?php
include("includes/admin_header.php");
include("includes/db.php");

// Fetching counselors with their images
$counselors = $conn->query("SELECT * FROM counselors");
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



<!-- Counselors Tab -->
<div class="container" id="counselors">
    <h3>Current Counselors</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Specialty</th>
                <th>Bio</th>
                <th>Degree</th>
                <th>Field</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $counselors->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Counselor Image"
                            style="width: 100px; height: auto;">
                    </td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['specialty']); ?></td>
                    <td><?php echo htmlspecialchars($row['bio']); ?></td>
                    <td><?php echo htmlspecialchars($row['degree']); ?></td>
                    <td><?php echo htmlspecialchars($row['field']); ?></td>
                    <td>
                        <a href="edit_counselor.php?id=<?php echo $row['id']; ?>"
                            style="color: orange; text-decoration: none; margin-right: 10px;">Edit</a>
                        <a href="delete_counselor.php?id=<?php echo $row['id']; ?>"
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