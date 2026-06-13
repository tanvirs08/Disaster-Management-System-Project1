<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $admin_query = mysqli_query($conn, "SELECT password FROM admins WHERE admin_id='$admin_id'");
    $admin = mysqli_fetch_assoc($admin_query);

    if (!password_verify($current_password, $admin['password'])) {
        $message = "<div class='message error'>Current password is incorrect!</div>";
    } elseif ($new_password !== $confirm_password) {
        $message = "<div class='message error'>New password and confirm password do not match!</div>";
    } elseif (strlen($new_password) < 6) {
        $message = "<div class='message error'>Password must be at least 6 characters long.</div>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update = "UPDATE admins SET password='$hashed_password' WHERE admin_id='$admin_id'";

        if (mysqli_query($conn, $update)) {
            $message = "<div class='message success'>Admin password changed successfully!</div>";
        } else {
            $message = "<div class='message error'>Failed to change password.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Change Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Admin Security</h2>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="admin-profile.php">Profile</a>
        <a href="admin-logout.php">Logout</a>
    </div>
</div>

<div class="dashboard">
    <h1>Change Admin Password</h1>
    <p>Update your admin account password securely.</p>

    <div class="form-container">
        <h2>Password Change Form</h2>

        <?php echo $message; ?>

        <form method="POST">
            <input type="password" name="current_password" placeholder="Current Password" required>

            <input type="password" name="new_password" placeholder="New Password" required>

            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>

            <button type="submit" class="btn">Change Password</button>
        </form>

        <p style="text-align:center; margin-top:15px;">
            <a href="admin-profile.php">Back to Admin Profile</a>
        </p>
    </div>
</div>

</body>
</html>