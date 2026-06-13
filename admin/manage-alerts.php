<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $alert_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM disaster_alerts WHERE alert_id=$alert_id");
    header("Location: manage-alerts.php");
    exit();
}

if (isset($_GET['inactive'])) {
    $alert_id = intval($_GET['inactive']);
    mysqli_query($conn, "UPDATE disaster_alerts SET status='Inactive' WHERE alert_id=$alert_id");
    header("Location: manage-alerts.php");
    exit();
}

if (isset($_GET['active'])) {
    $alert_id = intval($_GET['active']);
    mysqli_query($conn, "UPDATE disaster_alerts SET status='Active' WHERE alert_id=$alert_id");
    header("Location: manage-alerts.php");
    exit();
}

$alerts = mysqli_query($conn, "SELECT * FROM disaster_alerts ORDER BY alert_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Disaster Alerts</title>
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
    <h1>All Disaster Alerts</h1>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Type</th>
            <th>Location</th>
            <th>Severity</th>
            <th>Status</th>
            <th>Issued At</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($alerts)) { ?>
            <tr>
                <td><?php echo $row['alert_id']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['alert_type']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td><?php echo $row['severity']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['issued_at']; ?></td>
                <td>
                    <?php if ($row['status'] == 'Active') { ?>
                        <a class="small-btn warning-btn" href="manage-alerts.php?inactive=<?php echo $row['alert_id']; ?>">Inactive</a>
                    <?php } else { ?>
                        <a class="small-btn success-btn" href="manage-alerts.php?active=<?php echo $row['alert_id']; ?>">Active</a>
                    <?php } ?>

                    <a class="small-btn danger" 
                       onclick="return confirm('Are you sure you want to delete this alert?')"
                       href="manage-alerts.php?delete=<?php echo $row['alert_id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>