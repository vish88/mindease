<?php
include 'includes/header.php';
include 'includes/redirect.php';

if ($_SESSION['role'] !== 'client') {
    // Redirect if not client
    header("Location: index.php");
    exit();
}

?>


<main class="container mt-5">
    <h2 class="mb-4">Client Dashboard</h2>

    <?php include "includes/client_header.php"; ?>

</main>

<?php include 'includes/footer.php'; ?>