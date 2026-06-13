<?php
include "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $user_query = mysqli_query($conn, "SELECT password FROM users WHERE user_id='$user_id'");
    $user = mysqli_fetch_assoc($user_query);

    if (!password_verify($current_password, $user['password'])) {
        $message = "<div class='message error'>Current password is incorrect!</div>";
    } elseif ($new_password !== $confirm_password) {
        $message = "<div class='message error'>New password and confirm password do not match!</div>";
    } elseif (strlen($new_password) < 6) {
        $message = "<div class='message error'>Password must be at least 6 characters long.</div>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update = "UPDATE users SET password='$hashed_password' WHERE user_id='$user_id'";

        if (mysqli_query($conn, $update)) {
            $message = "<div class='message success'>Password changed successfully!</div>";
        } else {
            $message = "<div class='message error'>Failed to change password.</div>";
        }
    }
}
?>

<div class="dashboard">
    <h1>Change Password</h1>
    <p>Update your account password securely.</p>

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
            <a href="profile.php">Back to Profile</a>
        </p>
    </div>
</div>

<?php include "includes/footer.php"; ?>