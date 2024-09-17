<?php
include 'db.php'; // Include your database connection file

$default = "https://www.mondaycampaigns.org/wp-content/uploads/2020/04/destress-monday-feature-belly-breathing.png";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $degree = $_POST['degree'];
    $specialty = $_POST['specialty'];
    $field = $_POST['field'];
    $bio = $_POST['bio'];
    $image_url = isset($_POST['image']) ? $_POST['image'] : $default; // Default image URL if not provided

    // Validate the image URL (optional)
    if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
        $image_url = $default;
    }

    $query = "INSERT INTO counselors (name, degree, specialty, bio, image, field) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssss', $name, $degree, $specialty, $bio, $image_url, $field);

    if ($stmt->execute()) {
        echo 'Counselor added successfully!';
        header("Location: ../admin_dashboard.php");
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>