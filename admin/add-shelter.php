<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shelter_name = mysqli_real_escape_string($conn, $_POST['shelter_name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $capacity = intval($_POST['capacity']);
    $available_space = intval($_POST['available_space']);
    $contact_person = mysqli_real_escape_string($conn, $_POST['contact_person']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $facilities = mysqli_real_escape_string($conn, $_POST['facilities']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "INSERT INTO shelter_centers 
            (shelter_name, location, capacity, available_space, contact_person, phone, facilities, status)
            VALUES 
            ('$shelter_name', '$location', '$capacity', '$available_space', '$contact_person', '$phone', '$facilities', '$status')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div class='message success'>Shelter center added successfully!</div>";
    } else {
        $message = "<div class='message error'>Failed to add shelter center.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Shelter Center</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Add Shelter Center</h2>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage-shelters.php">Shelters</a>
        <a href="admin-profile.php">Profile</a>
        <a href="manage-contacts.php">Contacts</a>
        <a href="admin-logout.php">Logout</a>
    </div>
</div>

<div class="form-container">
    <h2>Shelter Center Form</h2>

    <?php echo $message; ?>

    <form method="POST">
        <input type="text" name="shelter_name" placeholder="Shelter Center Name" required>
<input type="text" name="location" list="districtList" placeholder="Select or type district" required>
<?php include "../includes/districts.php"; ?>
        <input type="number" name="capacity" placeholder="Total Capacity" required>
        <input type="number" name="available_space" placeholder="Available Space" required>
        <input type="text" name="contact_person" placeholder="Contact Person">
        <input type="text" name="phone" placeholder="Phone Number">
        <textarea name="facilities" placeholder="Facilities: Water, Food, Medical Support, Toilet, etc."></textarea>

        <select name="status" required>
            <option value="Open">Open</option>
            <option value="Closed">Closed</option>
            <option value="Full">Full</option>
        </select>

        <button type="submit" class="btn">Add Shelter</button>
    </form>
</div>

</body>
</html>