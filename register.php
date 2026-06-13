<?php
include "config/db.php";
include "includes/header.php";
include "includes/districts.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "<div class='message error'>Password does not match!</div>";
    } elseif (strlen($password) < 6) {
        $message = "<div class='message error'>Password must be at least 6 characters long.</div>";
    } else {
        $check_email = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($result) > 0) {
            $message = "<div class='message error'>Email already exists!</div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (full_name, email, phone, password, address)
                    VALUES ('$full_name', '$email', '$phone', '$hashed_password', '$address')";

            if (mysqli_query($conn, $sql)) {
                $message = "<div class='message success'>Registration successful! You can login now.</div>";
            } else {
                $message = "<div class='message error'>Registration failed: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}
?>

<div class="auth-page register-auth-page">
    <div class="auth-left">
        <span class="hero-badge">Create Account</span>
        <h1>Join the Smart Disaster Alert Platform</h1>
        <p>
            Register to receive disaster notifications, submit local reports,
            track safety alerts and access emergency information.
        </p>

        <div class="auth-feature-list">
            <p>✅ Submit disaster reports with image</p>
            <p>✅ Track your report status</p>
            <p>✅ Get alert notifications</p>
            <p>✅ Access emergency shelters and contacts</p>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-card register-card">
            <h2>User Registration</h2>
            <p class="auth-subtitle">Create your user account</p>

            <?php echo $message; ?>

            <form method="POST">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="Enter full name" required>

                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter email address" required>

                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="Enter phone number">

                <label>District / Address</label>
                <input type="text" name="address" list="districtList" placeholder="Select or type district">

                <label>Password</label>
                <input type="password" name="password" placeholder="Minimum 6 characters" required>

                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm password" required>

                <button type="submit" class="btn full-btn">Register</button>
            </form>

            <div class="auth-links">
                <p>Already have an account? <a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>