<?php
include 'header.php';
include 'includes/redirect.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<main class="container mt-5">
    <h2 class="mb-4">Admin Dashboard</h2>

    <header>

        <nav>
            <ul>

                <li>
                    <a href="add_counselor.php">
                        <i class="fas fa-user-plus"></i> Add Counselor
                    </a>

                </li>

                <li>
                    <a href="pending_payments.php">Pending
                        Payments</a>
                </li>
                <li>
                    <a href="complete_sessions.php">Completed Sessions</a>
                </li>
                <li>
                    <a href="counselors.php">Current Counselors</a>
                </li>
                <li>
                    <a href="clients.php">Client List</a>
                </li>
                <li>
                    <a href="upcoming_sessions.php">Upcoming
                        Sessions</a>
                </li>
                <li>
                    <a href="messages.php">Messages</a>
                </li>
                <li>
                    <a href="profile.php">
                        <i class="fas fa-user"></i> Profile
                    </a>

                </li>
                <li>

                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>

            </ul>
        </nav>
    </header>

</main>