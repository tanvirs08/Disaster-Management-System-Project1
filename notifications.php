<?php
include "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['read'])) {
    $notification_id = intval($_GET['read']);
    mysqli_query($conn, "UPDATE notifications SET is_read=1 WHERE notification_id=$notification_id AND user_id=$user_id");
    header("Location: notifications.php");
    exit();
}

if (isset($_GET['read_all'])) {
    mysqli_query($conn, "UPDATE notifications SET is_read=1 WHERE user_id=$user_id");
    header("Location: notifications.php");
    exit();
}

$notifications = mysqli_query($conn, "SELECT * FROM notifications WHERE user_id=$user_id ORDER BY notification_id DESC");
?>

<div class="dashboard">
    <h1>My Notifications</h1>
    <p>Latest disaster, weather and system notifications.</p>

    <br>

    <a href="notifications.php?read_all=1" class="btn">Mark All as Read</a>

    <div class="notification-list">
        <?php if (mysqli_num_rows($notifications) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($notifications)) { ?>
                <div class="notification-card <?php echo $row['is_read'] ? 'read' : 'unread'; ?>">
                    <h2><?php echo $row['title']; ?></h2>
                    <p><?php echo $row['message']; ?></p>
                    <p><strong>Type:</strong> <?php echo $row['type']; ?></p>
                    <p><strong>Date:</strong> <?php echo $row['created_at']; ?></p>

                    <?php if ($row['is_read'] == 0) { ?>
                        <a class="small-btn success-btn" href="notifications.php?read=<?php echo $row['notification_id']; ?>">
                            Mark as Read
                        </a>
                    <?php } else { ?>
                        <span class="status-badge verified">Read</span>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="message success">No notifications available.</div>
        <?php } ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>