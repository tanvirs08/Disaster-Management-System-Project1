<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $alert_type = mysqli_real_escape_string($conn, $_POST['alert_type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $severity = mysqli_real_escape_string($conn, $_POST['severity']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $instruction = mysqli_real_escape_string($conn, $_POST['instruction']);

    $sql = "INSERT INTO disaster_alerts 
            (title, alert_type, location, severity, description, instruction, status)
            VALUES 
            ('$title', '$alert_type', '$location', '$severity', '$description', '$instruction', 'Active')";

    if (mysqli_query($conn, $sql)) {

        // Send notification to all active users
        $users = mysqli_query($conn, "SELECT user_id FROM users WHERE status='active'");

        while ($user = mysqli_fetch_assoc($users)) {
            $user_id = $user['user_id'];
            $notification_title = "New Disaster Alert: " . $title;
            $notification_message = "Alert Type: " . $alert_type . " | Location: " . $location . " | Severity: " . $severity . ". Please check safety instructions.";

            $notify_sql = "INSERT INTO notifications 
                           (user_id, title, message, type, is_read)
                           VALUES 
                           ('$user_id', '$notification_title', '$notification_message', 'Disaster', 0)";

            mysqli_query($conn, $notify_sql);
        }

        $message = "<div class='message success'>Disaster alert added successfully and notification sent to users!</div>";

    } else {
        $message = "<div class='message error'>Failed to add alert!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Disaster Alert</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Add Disaster Alert</h2>
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

<div class="form-container">
    <h2>Create New Alert</h2>

    <?php echo $message; ?>

    <form method="POST">
        <input type="text" name="title" placeholder="Alert Title" required>

        <select name="alert_type" required>
            <option value="">Select Alert Type</option>
            <option value="Cyclone">Cyclone</option>
            <option value="Flood">Flood</option>
            <option value="Heavy Rain">Heavy Rain</option>
            <option value="Heatwave">Heatwave</option>
            <option value="Thunderstorm">Thunderstorm</option>
            <option value="Earthquake">Earthquake</option>
            <option value="Landslide">Landslide</option>
            <option value="Other">Other</option>
        </select>

<input type="text" name="location" list="districtList" placeholder="Select or type district" required>
<?php include "../includes/districts.php"; ?>
        <select name="severity" required>
            <option value="">Select Severity</option>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
            <option value="Critical">Critical</option>
        </select>

        <textarea name="description" placeholder="Alert Description" required></textarea>
        <textarea name="instruction" placeholder="Safety Instructions"></textarea>

        <button type="submit" class="btn">Publish Alert</button>
    </form>
</div>

</body>
</html>