=
<?php
include 'includes/header.php';
include 'includes/redirect.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include 'includes/db.php';
$id = $_GET['id'];

// Fetch counselor details
$counselor = $conn->query("SELECT * FROM counselors WHERE id = $id")->fetch_assoc();

// Update counselor details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $bio = $_POST['bio'];
    $degree = $_POST['degree'];
    $field = $_POST['field'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("UPDATE counselors SET name = ?, specialty = ?, bio = ?, degree = ?, field = ?, image = ? WHERE id = ?");
    $stmt->bind_param('ssssssi', $name, $specialty, $bio, $degree, $field, $image_url, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
    exit();
}

$conn->close();
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
        overflow: hidden;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-group textarea {
        resize: vertical;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        color: #fff;
        background-color: #007bff;
        cursor: pointer;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .text-center {
        text-align: center;
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
    }

    .mb-3 {
        margin-bottom: 15px;
    }
</style>

<main class="container mt-5">
    <h2>Edit Counselor</h2>
    <form action="edit_counselor.php?id=<?php echo htmlspecialchars($id); ?>" method="post">
        <div class="form-group text-center">
            <img src="<?php echo htmlspecialchars($counselor['image']); ?>" alt="Counselor Image" class="img-fluid mb-3"
                style="max-height: 300px;">
            <div class="form-group">
                <label for="image_url">Image URL:</label>
                <input type="text" id="image_url" name="image_url"
                    value="<?php echo htmlspecialchars($counselor['image']); ?>">
            </div>
        </div>
        <?php
        $fields = [
            'name' => 'Counselor Name',
            'specialty' => 'Specialty',
            'bio' => 'Bio',
            'degree' => 'Degree',
            'field' => 'Field'
        ];

        foreach ($fields as $key => $label) {
            $value = htmlspecialchars($counselor[$key]);
            $inputType = ($key === 'bio') ? 'textarea' : 'input';
            $rows = ($key === 'bio') ? 'rows="4"' : '';
            echo "
                <div class='form-group'>
                    <label for='$key'>$label:</label>
                    <$inputType id='$key' name='$key' $rows value='$value' required></$inputType>
                </div>
            ";
        }
        ?>
        <button type="submit" class="btn">Update Counselor</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>