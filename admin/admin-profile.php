<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$message = "";

$admin_query = mysqli_query($conn, "SELECT * FROM admins WHERE admin_id='$admin_id'");
$admin = mysqli_fetch_assoc($admin_query);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    $update = "UPDATE admins SET name='$name' WHERE admin_id='$admin_id'";

    if (mysqli_query($conn, $update)) {
        $_SESSION['admin_name'] = $name;
        $message = "<div class='message success'>Admin profile updated successfully!</div>";

        $admin_query = mysqli_query($conn, "SELECT * FROM admins WHERE admin_id='$admin_id'");
        $admin = mysqli_fetch_assoc($admin_query);
    } else {
        $message = "<div class='message error'>Failed to update admin profile.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Admin Profile</h2>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage-users.php">Users</a>
        <a href="add-alert.php">Add Alert</a>
        <a href="manage-alerts.php">Alerts</a>
        <a href="manage-reports.php">Reports</a>
        <a href="manage-contacts.php">Contacts</a>
        <a href="manage-shelters.php">Shelters</a>
        <a href="admin-profile.php">Profile</a>
        <a href="admin-logout.php">Logout</a>
    </div>
</div>

<div class="dashboard">
    <h1>My Admin Profile</h1>
    <p>View and update your admin account information.</p>

    <?php echo $message; ?>

    <div class="profile-layout">
        <div class="profile-card">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($admin['name'], 0, 1)); ?>
            </div>

            <h2><?php echo $admin['name']; ?></h2>
            <p><strong>Email:</strong> <?php echo $admin['email']; ?></p>
            <p><strong>Role:</strong> <?php echo $admin['role']; ?></p>
            <p><strong>Created At:</strong> <?php echo $admin['created_at']; ?></p>
        </div>

        <div class="form-container profile-form">
            <h2>Update Admin Profile</h2>

            <form method="POST">
                <input type="text" name="name" value="<?php echo $admin['name']; ?>" placeholder="Admin Name" required>

                <input type="email" value="<?php echo $admin['email']; ?>" disabled>

                <button type="submit" name="update_profile" class="btn">Update Profile</button>
            </form>

            <br>
            <a href="admin-change-password.php" class="btn">Change Password</a>
        </div>
    </div>
</div>

</body>
</html>