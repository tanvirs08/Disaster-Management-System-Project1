<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $shelter_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM shelter_centers WHERE shelter_id=$shelter_id");
    header("Location: manage-shelters.php");
    exit();
}

if (isset($_POST['update_shelter'])) {
    $shelter_id = intval($_POST['shelter_id']);
    $available_space = intval($_POST['available_space']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE shelter_centers 
                         SET available_space='$available_space', status='$status' 
                         WHERE shelter_id='$shelter_id'");

    header("Location: manage-shelters.php");
    exit();
}

$shelters = mysqli_query($conn, "SELECT * FROM shelter_centers ORDER BY shelter_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Shelter Centers</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Manage Shelter Centers</h2>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="add-shelter.php">Add Shelter</a>
        <a href="admin-profile.php">Profile</a>
        <a href="manage-contacts.php">Contacts</a>
        <a href="admin-logout.php">Logout</a>
    </div>
</div>

<div class="dashboard">
    <h1>All Shelter Centers</h1>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Shelter Name</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>Available</th>
            <th>Contact</th>
            <th>Phone</th>
            <th>Facilities</th>
            <th>Status Update</th>
            <th>Action</th>
        </tr>

        <?php if (mysqli_num_rows($shelters) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($shelters)) { ?>
                <tr>
                    <td><?php echo $row['shelter_id']; ?></td>
                    <td><?php echo $row['shelter_name']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['capacity']; ?></td>
                    <td><?php echo $row['available_space']; ?></td>
                    <td><?php echo $row['contact_person']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['facilities']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="shelter_id" value="<?php echo $row['shelter_id']; ?>">

                            <input type="number" name="available_space" value="<?php echo $row['available_space']; ?>" style="width:90px; padding:8px;">

                            <select name="status" style="padding:8px;">
                                <option value="Open" <?php if($row['status']=="Open") echo "selected"; ?>>Open</option>
                                <option value="Closed" <?php if($row['status']=="Closed") echo "selected"; ?>>Closed</option>
                                <option value="Full" <?php if($row['status']=="Full") echo "selected"; ?>>Full</option>
                            </select>

                            <button type="submit" name="update_shelter" class="small-btn success-btn" style="border:none; cursor:pointer;">
                                Update
                            </button>
                        </form>
                    </td>
                    <td>
                        <a class="small-btn danger"
                           onclick="return confirm('Delete this shelter?')"
                           href="manage-shelters.php?delete=<?php echo $row['shelter_id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="10">No shelter center found.</td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>