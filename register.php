<?php include 'includes/header.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'includes/db.php';
    session_start();

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $image = $role == "client" ?
        "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ57ZcfEJ1M2FoUe-4BneINLMpAb_ATqajDFQ&s" :
        "https://icons.iconarchive.com/icons/aha-soft/free-large-boss/512/Admin-icon.png";

    // Sanitize and validate input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT); // Hashing password

        // Check if user already exists
        $checkUser = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkUser->bind_param('s', $email);
        $checkUser->execute();
        $checkUser->store_result();
        if ($checkUser->num_rows > 0) {
            $error_message = "User already exists with this email.";
        } else {
            // Insert user into the database
            $sql = "INSERT INTO users (username, email, password, role, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssss', $name, $email, $password, $role, $image);

            if ($stmt->execute()) {
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role; // Store role in session
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkUser->close();
    }
    $conn->close();
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
        width: 80%;
        margin: auto;
        padding-top: 50px;
    }

    .form-container {
        max-width: 500px;
        margin: auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-group input[type="password"] {
        font-family: Arial, sans-serif;
    }

    .btn {
        display: block;
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 4px;
        color: #fff;
        background-color: #007bff;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        margin-top: 10px;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .alert {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<main class="container">
    <div class="form-container">
        <h2>Register</h2>
        <?php if (isset($error_message)) { ?>
            <div class="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php } ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="role">Register as:</label>
                <select id="role" name="role">
                    <option value="client">Client</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <a href="login.php" class="btn btn-secondary">Login</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>