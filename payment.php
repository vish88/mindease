<?php
include 'includes/header.php';
include 'includes/db.php';

// Encryption function
function encrypt_data($data)
{
    $encryption_key = 'your_secret_key'; // Replace with a secure key
    return openssl_encrypt($data, 'AES-128-ECB', $encryption_key);
}

// Get session ID and user ID
$session_id = $_GET['session_id'] ?? $_POST['session_id'];
$user_id = $_SESSION['user_id']; // Make sure the user is logged in

// If session ID or user ID is missing, redirect to the dashboard
if (!$session_id || !$user_id) {
    header("Location: client_dashboard.php");
    exit();
}

$session_fee = 30; // USD

// Server-side validation function
function validate_input($input, $pattern)
{
    return preg_match($pattern, $input);
}

// Process the form submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs
    $session_id = $_POST['session_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $card_number = $_POST['card_number'];
    $expiration_date = $_POST['expiration_date'];
    $cvv = $_POST['cvv'];

    // Validate card number (e.g., 16 digits)
    if (!validate_input($card_number, '/^[0-9]{16}$/')) {
        $errors[] = "Invalid card number. Please enter a 16-digit card number.";
    }

    // Validate expiration date (MM/YYYY format)
    // if (!validate_input($expiration_date, '/^(0[1-9]|1[0-2])\/\d{2}$/')) {
    //     $errors[] = "Invalid expiration date. Please enter a valid date in MM/YY format.";
    // } else {
    //     // Check if the expiration date is in the future
    //     $current_date = new DateTime();
    //     $expiry_date = DateTime::createFromFormat('m/y', $expiration_date);
    //     if ($expiry_date < $current_date) {
    //         $errors[] = "Expiration date must be in the future.";
    //     }
    // }

    // Validate CVV (e.g., 3 digits)
    if (!validate_input($cvv, '/^[0-9]{3}$/')) {
        $errors[] = "Invalid CVV. Please enter a 3-digit CVV code.";
    }

    // Validate payment method
    $valid_methods = ['visa', 'mastercard'];
    if (!in_array($payment_method, $valid_methods)) {
        $errors[] = "Invalid payment method.";
    }

    if (empty($errors)) {
        // Encrypt sensitive payment details
        $encrypted_card_number = encrypt_data($card_number);
        $encrypted_expiration_date = encrypt_data($expiration_date);
        $encrypted_cvv = encrypt_data($cvv);

        // Simulate payment processing
        $payment_successful = true; // Assume payment is successful

        if ($payment_successful) {
            // Insert payment details into the database
            $stmt = $conn->prepare("INSERT INTO payments (user_id, session_id, payment_method, card_number, expiration_date, cvv, payment_status) 
                                    VALUES (?, ?, ?, ?, ?, ?, 'paid')");
            $stmt->bind_param('iissss', $user_id, $session_id, $payment_method, $encrypted_card_number, $encrypted_expiration_date, $encrypted_cvv);

            if ($stmt->execute()) {
                // Update session payment status
                $stmt = $conn->prepare("UPDATE sessions SET payment_status = 'paid' WHERE id = ?");
                $stmt->bind_param('i', $session_id);
                $stmt->execute();

                // Redirect to the client dashboard
                header("Location: client_dashboard.php?payment_success=1");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Payment failed. Please try again.";
        }
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}

// Fetch session details to display
$stmt = $conn->prepare("SELECT * FROM sessions WHERE id = ?");
$stmt->bind_param('i', $session_id);
$stmt->execute();
$session = $stmt->get_result()->fetch_assoc();
$stmt->close();

$conn->close();
?>

<style>
    .container {
        width: 50%;
        margin: auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
</style>

<main class="container mt-5">
    <h2>Payment for Your Counseling Session</h2>
    <p>You are about to pay <strong>$<?php echo $session_fee; ?></strong> for the counseling session on
        <strong><?php echo $session['session_date']; ?></strong> at
        <strong><?php echo $session['session_time']; ?></strong>.
    </p>

    <form action="payment.php" method="post" onsubmit="return validateForm()">
        <input type="hidden" name="session_id" value="<?php echo htmlspecialchars($session_id); ?>">
        <!-- Pass session ID -->
        <input type="hidden" name="amount" value="<?php echo htmlspecialchars($session_fee); ?>">

        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select id="payment_method" name="payment_method" class="form-control" required>
                <option value="visa">Visa</option>
                <option value="mastercard">MasterCard</option>
            </select>
        </div>

        <div class="form-group">
            <label for="card_number">Card Number</label>
            <input type="text" id="card_number" name="card_number" class="form-control" required minlength="16"
                maxlength="16" pattern="\d{16}">
        </div>

        <div class="form-group">
            <label for="expiration_date">Expiration Date (MM/YY)</label>
            <input type="month" id="expiration_date" name="expiration_date" class="form-control" required>
            <small class="form-text text-muted">Please enter the date in MM/YY format.</small>
        </div>

        <div class="form-group">
            <label for="cvv">CVV</label>
            <input type="text" id="cvv" name="cvv" class="form-control" required minlength="3" maxlength="3"
                pattern="\d{3}">
        </div>

        <button type="submit" class="btn btn-success">Pay Now</button>
    </form>
</main>

<script>
    // Client-side validation using JavaScript
    function validateForm() {
        let cardNumber = document.getElementById('card_number').value;
        let expirationDate = document.getElementById('expiration_date').value;
        let cvv = document.getElementById('cvv').value;

        if (!/^\d{16}$/.test(cardNumber)) {
            alert('Please enter a valid 16-digit card number.');
            return false;
        }

        let today = new Date();
        let [month, year] = expirationDate.split('-');
        let expiry = new Date(year, month - 1); // Month is 0-based
        if (expiry < today) {
            alert('Please enter a valid future expiration date.');
            return false;
        }

        if (!/^\d{3}$/.test(cvv)) {
            alert('Please enter a valid 3-digit CVV.');
            return false;
        }

        return true;
    }
</script>

<?php include 'includes/footer.php'; ?>