<?php
session_start();
include "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_role'] = $admin['role'];

            header("Location: dashboard.php");
            exit();
        } else {
            $message = "<div class='message error'>Invalid password!</div>";
        }
    } else {
        $message = "<div class='message error'>Admin account not found!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Disaster Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="admin-auth-page">
    <div class="auth-card admin-auth-card">
        <span class="admin-badge">Admin Access</span>

        <h2>Admin Login</h2>
        <p class="auth-subtitle">Secure access for disaster management administrators</p>

        <?php echo $message; ?>

        <form method="POST">
            <label>Admin Email</label>
            <input type="email" name="email" placeholder="Enter admin email" required>

            <label>Admin Password</label>
            <input type="password" name="password" placeholder="Enter admin password" required>

            <button type="submit" class="btn full-btn">Login as Admin</button>
        </form>

        <div class="auth-links">
            <p><a href="../index.php">Back to Home</a></p>
            <p><a href="../login.php">User Login</a></p>
        </div>
    </div>
</div>

</body>
</html>