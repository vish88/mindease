<?php
include 'includes/header.php';
include 'includes/redirect.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if ($_SESSION['role'] !== 'client') {
    header("Location: index.php");
    exit();
}

include 'includes/db.php';

// Fetch available counselors
$counselors = $conn->query("SELECT * FROM counselors");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $counselor_id = $_POST['counselor_id'];
    $session_date = $_POST['session_date'];
    $session_time = $_POST['session_time'];

    // Make sure all fields are filled
    if ($counselor_id && $session_date && $session_time) {
        // Check if the chosen time slot is available
        $stmt = $conn->prepare("SELECT session_time FROM sessions WHERE counselor_id = ? AND session_date = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param('is', $counselor_id, $session_date);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result === false) {
            die('Execute failed: ' . htmlspecialchars($stmt->error));
        }
        $booked_slots = [];
        while ($row = $result->fetch_assoc()) {
            $booked_slots[] = $row['session_time'];
        }
        $stmt->close();

        // Check if the session time is already booked
        if (!in_array($session_time, $booked_slots)) {
            // Insert the new session
            $stmt = $conn->prepare("INSERT INTO sessions (client_id, counselor_id, session_date, session_time) VALUES (?, ?, ?, ?)");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param('iiss', $_SESSION['user_id'], $counselor_id, $session_date, $session_time);
            if ($stmt->execute()) {
                $session_id = $stmt->insert_id;
                header("Location: payment.php?session_id=" . $session_id);
                exit();
            } else {
                $error_message = "Unable to book session, please try again. " . htmlspecialchars($stmt->error);
            }
        } else {
            $error_message = "This time slot is already booked.";
        }
    } else {
        $error_message = "Please fill out all fields.";
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 50%;
        margin: auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .btn {
        display: inline-block;
        padding: 10px 15px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .alert {
        padding: 15px;
        background-color: #f44336;
        color: white;
        border-radius: 4px;
        margin-bottom: 15px;
    }
</style>

<?php include "includes/client_header.php"; ?>

<main class="container mt-5">
    <h2>Book a Session</h2>

    <!-- Display error message if any -->
    <?php if (isset($error_message)) { ?>
        <div class="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php } ?>

    <form id="bookingForm" action="book_session.php" method="post">
        <div class="form-group">
            <label for="counselor_id">Select Counselor:</label>
            <select id="counselor_id" name="counselor_id" required>
                <option value="">-- Select Counselor --</option>
                <?php while ($row = $counselors->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="session_date">Session Date:</label>
            <input type="date" id="session_date" name="session_date" required>
        </div>

        <div class="form-group">
            <label for="session_time">Session Time:</label>
            <input type="time" id="session_time" name="session_time" required>
        </div>

        <button type="submit" class="btn">Book Session</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>