<?php
// Check if a session is already started, and start it if not
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Professional online counseling services">
    <meta name="keywords" content="online counseling, mental health, therapy, counseling sessions">

    <title>Mindease Counseling</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">


    <!-- Font Awesome -->
    <link rel="stylesheet" href="styles.css">
    </style>

</head>

<body>
    <header>
        <div class="logo">Mindease <span class="tag">Counseling</span></div>
        <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>

        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['email'])): ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin_dashboard.php">Dashboard</a></li>
                    <?php elseif ($_SESSION['role'] === 'client'): ?>
                        <li><a href="client_dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <li><a href="about.php">About us</a></li>
                <li><a href="gallery.php">Media</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="faq.php">FAQs</a></li>
                <li><a href="contact.php">Contact</a></li>

            </ul>
        </nav>
    </header>