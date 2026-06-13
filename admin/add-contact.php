<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $service_type = mysqli_real_escape_string($conn, $_POST['service_type']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO emergency_contacts 
            (service_name, service_type, phone, email, location, description, status)
            VALUES 
            ('$service_name', '$service_type', '$phone', '$email', '$location', '$description', 'Active')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div class='message success'>Emergency contact added successfully!</div>";
    } else {
        $message = "<div class='message error'>Failed to add emergency contact.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Emergency Contact</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Add Emergency Contact</h2>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage-contacts.php">Contacts</a>
        <a href="manage-shelters.php">Shelters</a>
        <a href="admin-profile.php">Profile</a>
        <a href="admin-logout.php">Logout</a>
    </div>
</div>

<div class="form-container">
    <h2>Emergency Contact Form</h2>

    <?php echo $message; ?>

    <form method="POST">
        <input type="text" name="service_name" placeholder="Service Name" required>

        <select name="service_type" required>
            <option value="">Select Service Type</option>
            <option value="Fire Service">Fire Service</option>
            <option value="Police">Police</option>
            <option value="Hospital">Hospital</option>
            <option value="Ambulance">Ambulance</option>
            <option value="Rescue Team">Rescue Team</option>
            <option value="Cyclone Center">Cyclone Center</option>
            <option value="Volunteer Group">Volunteer Group</option>
            <option value="Other">Other</option>
        </select>

        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="email" name="email" placeholder="Email Address">
<input type="text" name="location" list="districtList" placeholder="Select or type district">
<?php include "../includes/districts.php"; ?>
        <textarea name="description" placeholder="Description"></textarea>

        <button type="submit" class="btn">Add Contact</button>
    </form>
</div>

</body>
</html>