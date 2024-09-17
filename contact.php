<?php include "includes/header.php"; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'includes/db.php';

    $first_name = htmlspecialchars($_POST['first-name']);
    $last_name = htmlspecialchars($_POST['last-name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    $stmt = $conn->prepare("INSERT INTO messages (first_name, last_name, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $first_name, $last_name, $email, $message);

    if ($stmt->execute()) {
        echo "Message sent successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<section class="contact-section">
    <div class="contact-text">
        <h2>Contact Us</h2>
        <p>Need to get in touch with us? Either fill out the form with your inquiry or find the <a
                href="mailto:contact@mindease.com">contact@mindease.com</a> you'd like to contact below.</p>
    </div>
    <div class="contact-form">
        <form action="contact.php" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="first-name">First name*</label>
                <input type="text" id="first-name" name="first-name" required>
            </div>
            <div class="form-group">
                <label for="last-name">Last name</label>
                <input type="text" id="last-name" name="last-name">
            </div>
            <div class="form-group">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message" required>What can we help you with?</label>
                <textarea id="message" name="message" rows="4" required></textarea>
            </div>
            <button class="button" type="submit">Submit</button>
        </form>
    </div>
</section>

<script>
    function validateForm() {
        var firstName = document.getElementById('first-name').value;
        var email = document.getElementById('email').value;
        var message = document.getElementById('message').value;

        if (!firstName || !email || !message) {
            alert('Please fill out all required fields.');
            return false;
        }
        return true;
    }
</script>


<?php include "includes/footer.php"; ?>