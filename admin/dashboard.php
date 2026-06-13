<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_alerts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM disaster_alerts"))['total'];
$active_alerts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM disaster_alerts WHERE status='Active'"))['total'];
$pending_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM disaster_reports WHERE status='Pending'"))['total'];
$verified_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM disaster_reports WHERE status='Verified'"))['total'];
$rejected_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM disaster_reports WHERE status='Rejected'"))['total'];
$total_predictions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM ai_predictions"))['total'];
$total_contacts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM emergency_contacts"))['total'];
$total_shelters = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM shelter_centers"))['total'];

$recent_reports = mysqli_query($conn, "SELECT disaster_reports.*, users.full_name 
                                       FROM disaster_reports 
                                       LEFT JOIN users ON disaster_reports.user_id = users.user_id 
                                       ORDER BY disaster_reports.report_id DESC 
                                       LIMIT 5");

$recent_alerts = mysqli_query($conn, "SELECT * FROM disaster_alerts ORDER BY alert_id DESC LIMIT 5");

$high_risk_predictions = mysqli_query($conn, "SELECT * FROM ai_predictions 
                                              WHERE risk_level IN ('High','Extreme') 
                                              ORDER BY prediction_id DESC 
                                              LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Analytics Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Admin Panel</h2>
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
    <h1>Welcome, <?php echo $_SESSION['admin_name']; ?></h1>
    <p>Advanced Disaster Management System analytics overview.</p>

    <div class="cards">
        <div class="card stat-blue">
            <h3>Total Users</h3>
            <h2><?php echo $total_users; ?></h2>
        </div>

        <div class="card stat-orange">
            <h3>Total Alerts</h3>
            <h2><?php echo $total_alerts; ?></h2>
        </div>

        <div class="card stat-red">
            <h3>Active Alerts</h3>
            <h2><?php echo $active_alerts; ?></h2>
        </div>

        <div class="card stat-yellow">
            <h3>Pending Reports</h3>
            <h2><?php echo $pending_reports; ?></h2>
        </div>

        <div class="card stat-green">
            <h3>Verified Reports</h3>
            <h2><?php echo $verified_reports; ?></h2>
        </div>

        <div class="card stat-red">
            <h3>Rejected Reports</h3>
            <h2><?php echo $rejected_reports; ?></h2>
        </div>

        <div class="card stat-purple">
            <h3>AI Predictions</h3>
            <h2><?php echo $total_predictions; ?></h2>
        </div>

        <div class="card stat-blue">
            <h3>Emergency Contacts</h3>
            <h2><?php echo $total_contacts; ?></h2>
        </div>

        <div class="card stat-green">
            <h3>Shelter Centers</h3>
            <h2><?php echo $total_shelters; ?></h2>
        </div>
    </div>

    <div class="analytics-section">
        <div class="analytics-box">
            <h2>Report Status Analytics</h2>

            <div class="progress-item">
                <p>Pending Reports: <?php echo $pending_reports; ?></p>
                <div class="progress-bar">
                    <div class="progress-fill pending-fill" style="width: <?php echo min($pending_reports * 10, 100); ?>%;"></div>
                </div>
            </div>

            <div class="progress-item">
                <p>Verified Reports: <?php echo $verified_reports; ?></p>
                <div class="progress-bar">
                    <div class="progress-fill verified-fill" style="width: <?php echo min($verified_reports * 10, 100); ?>%;"></div>
                </div>
            </div>

            <div class="progress-item">
                <p>Rejected Reports: <?php echo $rejected_reports; ?></p>
                <div class="progress-bar">
                    <div class="progress-fill rejected-fill" style="width: <?php echo min($rejected_reports * 10, 100); ?>%;"></div>
                </div>
            </div>
        </div>

        <div class="analytics-box">
            <h2>System Summary</h2>
            <p><strong>Total Registered Users:</strong> <?php echo $total_users; ?></p>
            <p><strong>Total Disaster Alerts:</strong> <?php echo $total_alerts; ?></p>
            <p><strong>Active Disaster Alerts:</strong> <?php echo $active_alerts; ?></p>
            <p><strong>Total AI Predictions:</strong> <?php echo $total_predictions; ?></p>
            <p><strong>Emergency Contacts:</strong> <?php echo $total_contacts; ?></p>
            <p><strong>Shelter Centers:</strong> <?php echo $total_shelters; ?></p>
        </div>
    </div>

    <div class="analytics-section">
        <div class="analytics-box">
            <h2>Recent Disaster Reports</h2>

            <table class="data-table">
                <tr>
                    <th>User</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Status</th>
                </tr>

                <?php if (mysqli_num_rows($recent_reports) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($recent_reports)) { ?>
                        <tr>
                            <td><?php echo $row['full_name']; ?></td>
                            <td><?php echo $row['disaster_type']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower($row['status']); ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">No recent reports found.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="analytics-box">
            <h2>Recent Disaster Alerts</h2>

            <table class="data-table">
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Severity</th>
                    <th>Status</th>
                </tr>

                <?php if (mysqli_num_rows($recent_alerts) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($recent_alerts)) { ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['alert_type']; ?></td>
                            <td><?php echo $row['severity']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">No alerts found.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <div class="analytics-section">
        <div class="analytics-box full-width-box">
            <h2>High Risk AI Predictions</h2>

            <table class="data-table">
                <tr>
                    <th>Location</th>
                    <th>Temperature</th>
                    <th>Rainfall</th>
                    <th>Wind Speed</th>
                    <th>Predicted Disaster</th>
                    <th>Risk</th>
                    <th>Date</th>
                </tr>

                <?php if (mysqli_num_rows($high_risk_predictions) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($high_risk_predictions)) { ?>
                        <tr>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo $row['temperature']; ?> °C</td>
                            <td><?php echo $row['rainfall']; ?> mm</td>
                            <td><?php echo $row['wind_speed']; ?> km/h</td>
                            <td><?php echo $row['predicted_disaster']; ?></td>
                            <td>
                                <span class="risk-badge <?php echo strtolower($row['risk_level']); ?>">
                                    <?php echo $row['risk_level']; ?>
                                </span>
                            </td>
                            <td><?php echo $row['prediction_date']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7">No high risk prediction found.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<br><br>
<a href="admin-profile.php" class="btn">My Admin Profile</a>
<a href="admin-change-password.php" class="btn">Change Password</a>

</body>
</html>