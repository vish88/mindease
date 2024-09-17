<?php include 'includes/header.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'includes/db.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Prepare and execute the query
        $sql = "SELECT id, password, role FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $hashed_password, $role);
            $stmt->fetch();

            if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
                // Set session variables
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role; // Store role for access control

                header("Location: index.php");
                exit();
            } else {
                $error_message = "Invalid email or password.";
            }
            $stmt->close();
        } else {
            $error_message = "Error preparing the query.";
        }
        $conn->close();
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
        width: 80%;
        margin: auto;
        padding-top: 50px;
    }

    .login-form {
        max-width: 400px;
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

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-sizing: border-box;
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
        margin-bottom: 4px;
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
    <div class="login-form">
        <h2>Login</h2>
        <?php if (isset($error_message)) { ?>
            <div class="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php } ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <a href="register.php" class="btn btn-secondary">Register</a>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>