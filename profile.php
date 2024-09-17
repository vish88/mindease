<?php include 'includes/header.php'; ?>

<main
    style="max-width: 900px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
    
    
    <?php
// Check if a session is already started, and start it if not
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    include 'includes/db.php';

    if (!isset($_SESSION['email'])) {
        echo '<div style="color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 4px; margin-bottom: 20px;">Please <a href="login.php" style="color: #856404; text-decoration: underline;">login</a> to access this page.</div>';
        exit();
    }

    $email = $_SESSION['email'];
    $role = $_SESSION['role'];

    if ($role === "admin") {
        include "includes/admin_header.php";
    } else {
        include "includes/client_header.php";
    }

    // Fetch user details
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Handle profile update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_details'])) {
            $username = $_POST['username'];
            $new_password = $_POST['new_password'];
            $hobbies = $_POST['hobbies'];
            $is_married = isset($_POST['is_married']) ? 1 : 0;
            $nationality = $_POST['nationality'];
            $image_url = $_POST['image_url'];

            if (!empty($username)) {
                $stmt = $conn->prepare("UPDATE users SET username = ? WHERE email = ?");
                $stmt->bind_param('ss', $username, $email);
                $stmt->execute();
                $stmt->close();
            }

            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->bind_param('ss', $hashed_password, $email);
                $stmt->execute();
                $stmt->close();
            }

            $stmt = $conn->prepare("UPDATE users SET hobbies = ?, is_married = ?, nationality = ?, image = ? WHERE email = ?");
            $stmt->bind_param('sisss', $hobbies, $is_married, $nationality, $image_url, $email);
            $stmt->execute();

            echo '<div style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 20px;">Profile updated successfully.</div>';
            header("Location: profile.php");
        } elseif (isset($_POST['change_role'])) {
            $new_role = $_POST['new_role'];
            $current_password = $_POST['current_password'];

            // Check if the current password is correct
            $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();

            if (password_verify($current_password, $user_data['password'])) {
                if ($role === 'admin' && $new_role === 'client') {
                    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE email = ?");
                    $stmt->bind_param('ss', $new_role, $email);
                    $stmt->execute();
                    echo '<div style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 20px;">Role updated successfully. You will need to re-login to see the changes.</div>';
                } elseif ($role === 'client' && $new_role === 'admin') {
                    echo '<div style="color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 4px; margin-bottom: 20px;">Clients cannot upgrade to admin roles.</div>';
                } else {
                    echo '<div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 20px;">Invalid role change.</div>';
                }
            } else {
                echo '<div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 20px;">Incorrect password.</div>';
            }
        } elseif (isset($_POST['delete_account'])) {
            $password = $_POST['password'];

            // Check if the password is correct
            $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();

            if (password_verify($password, $user_data['password'])) {
                $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
                $stmt->bind_param('s', $email);
                $stmt->execute();
                session_destroy();
                header("Location: index.php");
                exit();
            } else {
                echo '<div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 20px;">Incorrect password.</div>';
            }
        }
    }

    $stmt->close();
    $conn->close();
    ?>

    <div
        style="border: 1px solid #ddd; border-radius: 8px; background-color: #fff; padding: 20px; margin-bottom: 20px;">
        <h2 style="margin-top: 0;">Your Profile</h2>
        <div style="text-align: center; margin-bottom: 20px;">
            <!-- Display the current user image -->
            <img src="<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Image"
                style="max-height: 200px; max-width: 200px; border-radius: 50%; border: 1px solid #ddd;">
        </div>
        <div style="display: flex; justify-content: space-between;">
            <div style="width: 48%;">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Role:</strong> <?php echo htmlspecialchars($role); ?></p>
                <p><strong>Hobbies:</strong> <?php echo htmlspecialchars($user['hobbies']); ?></p>
                <p><strong>Married:</strong> <?php echo htmlspecialchars($user['is_married'] ? 'Yes' : 'No'); ?></p>
                <p><strong>Nationality:</strong> <?php echo htmlspecialchars($user['nationality']); ?></p>
            </div>
            <div style="width: 48%;">
                <h3>Update Profile</h3>
                <form action="profile.php" method="post">
                    <div style="margin-bottom: 15px;">
                        <label for="username">Change Username:</label>
                        <input type="text" id="username" name="username"
                            style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                            value="<?php echo htmlspecialchars($user['username']); ?>">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="new_password">Change Password:</label>
                        <input type="password" id="new_password" name="new_password"
                            style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <small style="color: #6c757d;">Leave blank if you don't want to change your password.</small>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="hobbies">Hobbies:</label>
                        <input type="text" id="hobbies" name="hobbies"
                            style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                            value="<?php echo htmlspecialchars($user['hobbies']); ?>">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <input type="checkbox" id="is_married" name="is_married" <?php echo $user['is_married'] ? 'checked' : ''; ?>>
                        <label for="is_married">Married</label>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="nationality">Nationality:</label>
                        <input type="text" id="nationality" name="nationality"
                            style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                            value="<?php echo htmlspecialchars($user['nationality']); ?>">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="image_url">Profile Image URL:</label>
                        <input type="text" id="image_url" name="image_url"
                            style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                            value="<?php echo htmlspecialchars($user['image']); ?>">
                    </div>
                    <button type="submit" name="update_details"
                        style="padding: 10px 15px; border: none; border-radius: 4px; background-color: #007bff; color: white; cursor: pointer;">Update
                        Profile</button>
                </form>
            </div>
        </div>
    </div>

    <?php if ($role === 'admin'): ?>
        <div
            style="border: 1px solid #ddd; border-radius: 8px; background-color: #fff; padding: 20px; margin-bottom: 20px;">
            <h3>Change Role</h3>
            <form action="profile.php" method="post">
                <div style="margin-bottom: 15px;">
                    <label for="new_role">New Role:</label>
                    <select id="new_role" name="new_role"
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="client">Client</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password"
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                </div>
                <button type="submit" name="change_role"
                    style="padding: 10px 15px; border: none; border-radius: 4px; background-color: #007bff; color: white; cursor: pointer;">Change
                    Role</button>
            </form>
        </div>
    <?php endif; ?>

    <div style="border: 1px solid #ddd; border-radius: 8px; background-color: #fff; padding: 20px;">
        <h3>Delete Account</h3>
        <form action="profile.php" method="post">
            <div style="margin-bottom: 15px;">
                <label for="password">Enter Your Password:</label>
                <input type="password" id="password" name="password"
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
            </div>
            <button type="submit" name="delete_account"
                style="padding: 10px 15px; border: none; border-radius: 4px; background-color: #dc3545; color: white; cursor: pointer;">Delete
                Account</button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>