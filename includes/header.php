<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$unread_notifications = 0;

if (isset($_SESSION['user_id'])) {
    include_once "config/db.php";
    $uid = $_SESSION['user_id'];
    $noti_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM notifications WHERE user_id=$uid AND is_read=0");
    if ($noti_query) {
        $unread_notifications = mysqli_fetch_assoc($noti_query)['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Disaster Management System</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Disaster Management System</h2>
    <div>
        <a href="index.php">Home</a>
        <a href="weather.php">Weather</a>
        <a href="weather-history.php">Weather History</a>
        <a href="forecast.php">Forecast</a>
        <a href="alerts.php">Alerts</a>
        <a href="verified-reports.php">Reports</a>
        <a href="emergency.php">Emergency</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="shelters.php">Shelters</a>
        <a href="ai-prediction.php">AI Prediction</a>

        <?php if(isset($_SESSION['user_id'])) { ?>
    <a href="report-disaster.php">Report Disaster</a>
    <a href="my-reports.php">My Reports</a>

    <a href="notifications.php">
        Notifications
        <?php if ($unread_notifications > 0) { ?>
            <span class="notification-badge"><?php echo $unread_notifications; ?></span>
        <?php } ?>
    </a>
     <a href="profile.php">Profile</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
<?php } else { ?>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
<?php } ?>
    </div>
</div>