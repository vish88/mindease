<?php
include("includes/admin_header.php");
?>

<style>
    /* Main container */
    .container {
        width: 80%;
        margin: 50px auto;
        /* Similar to Bootstrap's mt-5 */
    }

    .mb-4 {
        margin-bottom: 20px;
        /* Adjust the spacing as needed */
    }
</style>

<!-- Add Counselor Form -->
<div class="container mt-4">
    <h3>Add Counselor</h3>
    <form action="includes/admin_process.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Counselor Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="degree">Highest Degree:</label>
            <input type="text" id="degree" name="degree" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="field">Field:</label>
            <input type="text" id="field" name="field" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="specialty">Specialty:</label>
            <input type="text" id="specialty" name="specialty" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Profile Image URL:</label>
            <input type="text" id="image" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Counselor</button>
    </form>
</div>

<?php
include("includes/footer.php");
?>