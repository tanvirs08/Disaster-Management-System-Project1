<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$message = "";

if (isset($_POST['update_user'])) {
    $user_id = intval($_POST['user_id']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update = "UPDATE users SET role='$role', status='$status' WHERE user_id=$user_id";

    if (mysqli_query($conn, $update)) {
        $message = "<div class='message success'>User updated successfully!</div>";
    } else {
        $message = "<div class='message error'>Failed to update user.</div>";
    }
}

if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM notifications WHERE user_id=$user_id");
    mysqli_query($conn, "DELETE FROM disaster_reports WHERE user_id=$user_id");
    mysqli_query($conn, "DELETE FROM users WHERE user_id=$user_id");

    header("Location: manage-users.php");
    exit();
}

$search = "";
$where = "WHERE 1";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND (full_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%')";
}

$users = mysqli_query($conn, "SELECT * FROM users $where ORDER BY user_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Manage Users</h2>
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
    <h1>All Registered Users</h1>
    <p>Search, update role/status, and manage user accounts.</p>

    <?php echo $message; ?>

    <form method="GET" class="filter-box">
        <input type="text" name="search" placeholder="Search by name, email, phone" value="<?php echo $search; ?>">
        <button type="submit" class="btn">Search</button>
        <a href="manage-users.php" class="btn reset-btn">Reset</a>
    </form>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>User Info</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Joined</th>
            <th>Role/Status Update</th>
            <th>Action</th>
        </tr>

        <?php if (mysqli_num_rows($users) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($users)) { ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>

                    <td>
                        <strong><?php echo $row['full_name']; ?></strong><br>
                        <small><?php echo $row['email']; ?></small>
                    </td>

                    <td><?php echo $row['phone']; ?></td>

                    <td><?php echo $row['address']; ?></td>

                    <td><?php echo $row['created_at']; ?></td>

                    <td>
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">

                            <select name="role" style="padding:8px; margin-bottom:7px;">
                                <option value="user" <?php if($row['role']=="user") echo "selected"; ?>>User</option>
                                <option value="volunteer" <?php if($row['role']=="volunteer") echo "selected"; ?>>Volunteer</option>
                            </select>

                            <select name="status" style="padding:8px; margin-bottom:7px;">
                                <option value="active" <?php if($row['status']=="active") echo "selected"; ?>>Active</option>
                                <option value="blocked" <?php if($row['status']=="blocked") echo "selected"; ?>>Blocked</option>
                            </select>

                            <button type="submit" name="update_user" class="small-btn success-btn" style="border:none; cursor:pointer;">
                                Update
                            </button>
                        </form>
                    </td>

                    <td>
                        <a class="small-btn info-btn" href="user-details.php?id=<?php echo $row['user_id']; ?>">Details</a>

                        <a class="small-btn danger"
                           onclick="return confirm('Are you sure you want to delete this user? This will also delete user reports and notifications.')"
                           href="manage-users.php?delete=<?php echo $row['user_id']; ?>">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="7">No user found.</td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>