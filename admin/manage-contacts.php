<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $contact_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM emergency_contacts WHERE contact_id=$contact_id");
    header("Location: manage-contacts.php");
    exit();
}

if (isset($_GET['inactive'])) {
    $contact_id = intval($_GET['inactive']);
    mysqli_query($conn, "UPDATE emergency_contacts SET status='Inactive' WHERE contact_id=$contact_id");
    header("Location: manage-contacts.php");
    exit();
}

if (isset($_GET['active'])) {
    $contact_id = intval($_GET['active']);
    mysqli_query($conn, "UPDATE emergency_contacts SET status='Active' WHERE contact_id=$contact_id");
    header("Location: manage-contacts.php");
    exit();
}

$contacts = mysqli_query($conn, "SELECT * FROM emergency_contacts ORDER BY contact_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Emergency Contacts</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Manage Emergency Contacts</h2>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="add-contact.php">Add Contact</a>
        <a href="manage-shelters.php">Shelters</a>
        <a href="admin-profile.php">Profile</a>
        <a href="admin-logout.php">Logout</a>
    </div>
</div>

<div class="dashboard">
    <h1>All Emergency Contacts</h1>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Service</th>
            <th>Type</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Location</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php if (mysqli_num_rows($contacts) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($contacts)) { ?>
                <tr>
                    <td><?php echo $row['contact_id']; ?></td>
                    <td><?php echo $row['service_name']; ?></td>
                    <td><?php echo $row['service_type']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'Active') { ?>
                            <a class="small-btn warning-btn" href="manage-contacts.php?inactive=<?php echo $row['contact_id']; ?>">Inactive</a>
                        <?php } else { ?>
                            <a class="small-btn success-btn" href="manage-contacts.php?active=<?php echo $row['contact_id']; ?>">Active</a>
                        <?php } ?>

                        <a class="small-btn danger"
                           onclick="return confirm('Delete this contact?')"
                           href="manage-contacts.php?delete=<?php echo $row['contact_id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="8">No emergency contact found.</td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>