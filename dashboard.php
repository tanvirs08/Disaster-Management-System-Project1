<?php
include "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$total_alerts_query = "SELECT COUNT(*) AS total FROM disaster_alerts WHERE status='Active'";
$total_alerts_result = mysqli_query($conn, $total_alerts_query);
$total_alerts = mysqli_fetch_assoc($total_alerts_result)['total'];

$total_reports_query = "SELECT COUNT(*) AS total FROM disaster_reports WHERE user_id=" . $_SESSION['user_id'];
$total_reports_result = mysqli_query($conn, $total_reports_query);
$total_reports = mysqli_fetch_assoc($total_reports_result)['total'];

$total_notifications_query = "SELECT COUNT(*) AS total FROM notifications WHERE user_id=" . $_SESSION['user_id'] . " AND is_read=0";
$total_notifications_result = mysqli_query($conn, $total_notifications_query);
$total_notifications = mysqli_fetch_assoc($total_notifications_result)['total'];
?>

<div class="dashboard">
    <h1>Welcome, <?php echo $_SESSION['full_name']; ?></h1>
    <p>This is your disaster management dashboard.</p>

    <div class="cards">
        <div class="card">
            <h3>Active Alerts</h3>
            <h2><?php echo $total_alerts; ?></h2>
        </div>

        <div class="card">
            <h3>My Reports</h3>
            <h2><?php echo $total_reports; ?></h2>
        </div>

        <div class="card">
    <h3>Notifications</h3>
    <h2><?php echo $total_notifications; ?></h2>
    <br>
    <a href="notifications.php" class="small-btn info-btn">View</a>
</div>

        

        <div class="card">
            <h3>AI Risk</h3>
            <h2>Coming Soon</h2>
        </div>
    </div>

    <br><br>
    <a href="profile.php" class="btn">My Profile</a>
    <a href="report-disaster.php" class="btn">Report Disaster</a>
<a href="my-reports.php" class="btn">My Reports</a>
<a href="weather.php" class="btn">Weather</a>
<a href="forecast.php" class="btn">Forecast</a>
<a href="ai-prediction.php" class="btn">AI Prediction</a>
    <a href="logout.php" class="btn">Logout</a>
</div>

<?php include "includes/footer.php"; ?>