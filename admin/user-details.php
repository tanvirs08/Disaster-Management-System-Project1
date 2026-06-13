<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage-users.php");
    exit();
}

$user_id = intval($_GET['id']);

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id=$user_id");

if (mysqli_num_rows($user_query) == 0) {
    echo "User not found.";
    exit();
}

$user = mysqli_fetch_assoc($user_query);

$total_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM disaster_reports WHERE user_id=$user_id"))['total'];
$pending_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM disaster_reports WHERE user_id=$user_id AND status='Pending'"))['total'];
$verified_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM disaster_reports WHERE user_id=$user_id AND status='Verified'"))['total'];
$total_notifications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM notifications WHERE user_id=$user_id"))['total'];

$reports = mysqli_query($conn, "SELECT * FROM disaster_reports WHERE user_id=$user_id ORDER BY report_id DESC LIMIT 10");
$notifications = mysqli_query($conn, "SELECT * FROM notifications WHERE user_id=$user_id ORDER BY notification_id DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Details</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>User Details</h2>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage-users.php">Back to Users</a>
        <a href="admin-logout.php">Logout</a>
    </div>
</div>

<div class="dashboard">
    <h1><?php echo $user['full_name']; ?></h1>
    <p>Detailed user information and activity summary.</p>

    <div class="profile-layout">
        <div class="profile-card">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
            </div>

            <h2><?php echo $user['full_name']; ?></h2>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
            <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
            <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
            <p><strong>Status:</strong>
                <span class="status-badge <?php echo strtolower($user['status']); ?>">
                    <?php echo $user['status']; ?>
                </span>
            </p>
            <p><strong>Joined:</strong> <?php echo $user['created_at']; ?></p>
        </div>

        <div>
            <div class="cards" style="grid-template-columns: repeat(2, 1fr);">
                <div class="card stat-blue">
                    <h3>Total Reports</h3>
                    <h2><?php echo $total_reports; ?></h2>
                </div>

                <div class="card stat-yellow">
                    <h3>Pending Reports</h3>
                    <h2><?php echo $pending_reports; ?></h2>
                </div>

                <div class="card stat-green">
                    <h3>Verified Reports</h3>
                    <h2><?php echo $verified_reports; ?></h2>
                </div>

                <div class="card stat-purple">
                    <h3>Notifications</h3>
                    <h2><?php echo $total_notifications; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="analytics-section">
        <div class="analytics-box">
            <h2>Recent Reports</h2>

            <table class="data-table">
                <tr>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>

                <?php if (mysqli_num_rows($reports) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($reports)) { ?>
                        <tr>
                            <td><?php echo $row['disaster_type']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower($row['status']); ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td><?php echo $row['reported_at']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">No reports found.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="analytics-box">
            <h2>Recent Notifications</h2>

            <table class="data-table">
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Read</th>
                    <th>Date</th>
                </tr>

                <?php if (mysqli_num_rows($notifications) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($notifications)) { ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['type']; ?></td>
                            <td><?php echo $row['is_read'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">No notifications found.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>