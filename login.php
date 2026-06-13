<?php
include "config/db.php";
include "includes/header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND status='active'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];

            header("Location: dashboard.php");
            exit();
        } else {
            $message = "<div class='message error'>Invalid password!</div>";
        }
    } else {
        $message = "<div class='message error'>No active account found with this email!</div>";
    }
}
?>

<div class="auth-page">
    <div class="auth-left">
        <span class="hero-badge">Welcome Back</span>
        <h1>Login to Disaster Management System</h1>
        <p>
            Access live weather updates, disaster alerts, AI risk prediction,
            notifications, reports and emergency services.
        </p>

        <div class="auth-feature-list">
            <p>✅ Real-time disaster alerts</p>
            <p>✅ Weather forecast and risk analysis</p>
            <p>✅ Submit and track disaster reports</p>
            <p>✅ Receive emergency notifications</p>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <h2>User Login</h2>
            <p class="auth-subtitle">Enter your account information</p>

            <?php echo $message; ?>

            <form method="POST">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>

                <button type="submit" class="btn full-btn">Login</button>
            </form>

            <div class="auth-links">
                <p>Don't have an account? <a href="register.php">Create Account</a></p>
                <p>Are you admin? <a href="admin/admin-login.php">Admin Login</a></p>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>