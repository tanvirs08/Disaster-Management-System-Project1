<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

if (isset($_POST['update_status'])) {
    $report_id = intval($_POST['report_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $admin_note = mysqli_real_escape_string($conn, $_POST['admin_note']);

    mysqli_query($conn, "UPDATE disaster_reports 
                         SET status='$status', admin_note='$admin_note' 
                         WHERE report_id='$report_id'");

    header("Location: manage-reports.php");
    exit();
}

$search = "";
$status_filter = "";
$type_filter = "";

$where = "WHERE 1";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND (disaster_reports.location LIKE '%$search%' OR disaster_reports.description LIKE '%$search%' OR users.full_name LIKE '%$search%' OR users.email LIKE '%$search%')";
}

if (isset($_GET['status']) && $_GET['status'] != "") {
    $status_filter = mysqli_real_escape_string($conn, $_GET['status']);
    $where .= " AND disaster_reports.status='$status_filter'";
}

if (isset($_GET['type']) && $_GET['type'] != "") {
    $type_filter = mysqli_real_escape_string($conn, $_GET['type']);
    $where .= " AND disaster_reports.disaster_type='$type_filter'";
}

$reports = mysqli_query($conn, "SELECT disaster_reports.*, users.full_name, users.email 
                                FROM disaster_reports 
                                LEFT JOIN users ON disaster_reports.user_id = users.user_id
                                $where
                                ORDER BY disaster_reports.report_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Disaster Reports</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Manage Reports</h2>
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
    <h1>User Disaster Reports</h1>
    <p>Search, filter, verify or reject submitted disaster reports.</p>

    <form method="GET" class="filter-box">
        <input type="text" name="search" placeholder="Search user, email, location, description" value="<?php echo $search; ?>">

        <select name="status">
            <option value="">All Status</option>
            <option value="Pending" <?php if($status_filter=="Pending") echo "selected"; ?>>Pending</option>
            <option value="Verified" <?php if($status_filter=="Verified") echo "selected"; ?>>Verified</option>
            <option value="Rejected" <?php if($status_filter=="Rejected") echo "selected"; ?>>Rejected</option>
        </select>

        <select name="type">
            <option value="">All Types</option>
            <option value="Flood" <?php if($type_filter=="Flood") echo "selected"; ?>>Flood</option>
            <option value="Cyclone" <?php if($type_filter=="Cyclone") echo "selected"; ?>>Cyclone</option>
            <option value="Heavy Rain" <?php if($type_filter=="Heavy Rain") echo "selected"; ?>>Heavy Rain</option>
            <option value="Fire" <?php if($type_filter=="Fire") echo "selected"; ?>>Fire</option>
            <option value="Earthquake" <?php if($type_filter=="Earthquake") echo "selected"; ?>>Earthquake</option>
            <option value="Landslide" <?php if($type_filter=="Landslide") echo "selected"; ?>>Landslide</option>
            <option value="Road Block" <?php if($type_filter=="Road Block") echo "selected"; ?>>Road Block</option>
            <option value="Other" <?php if($type_filter=="Other") echo "selected"; ?>>Other</option>
        </select>

        <button type="submit" class="btn">Filter</button>
        <a href="manage-reports.php" class="btn reset-btn">Reset</a>
    </form>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Type</th>
            <th>Location</th>
            <th>Description</th>
            <th>Image</th>
            <th>Status</th>
            <th>Admin Action</th>
        </tr>

        <?php if (mysqli_num_rows($reports) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($reports)) { ?>
                <tr>
                    <td><?php echo $row['report_id']; ?></td>
                    <td>
                        <?php echo $row['full_name']; ?><br>
                        <small><?php echo $row['email']; ?></small>
                    </td>
                    <td><?php echo $row['disaster_type']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <?php if (!empty($row['image'])) { ?>
                            <a href="../assets/uploads/<?php echo $row['image']; ?>" target="_blank">
                                <img src="../assets/uploads/<?php echo $row['image']; ?>" width="80" height="60" style="object-fit:cover; border-radius:6px;">
                            </a>
                        <?php } else { ?>
                            No Image
                        <?php } ?>
                    </td>
                    <td>
                        <span class="status-badge <?php echo strtolower($row['status']); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">

                            <select name="status" required style="padding:8px; width:130px; margin-bottom:8px;">
                                <option value="Pending" <?php if($row['status']=="Pending") echo "selected"; ?>>Pending</option>
                                <option value="Verified" <?php if($row['status']=="Verified") echo "selected"; ?>>Verified</option>
                                <option value="Rejected" <?php if($row['status']=="Rejected") echo "selected"; ?>>Rejected</option>
                            </select>

                            <textarea name="admin_note" placeholder="Admin note" style="width:160px; height:55px;"><?php echo $row['admin_note']; ?></textarea>

                            <button type="submit" name="update_status" class="small-btn success-btn" style="border:none; cursor:pointer;">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="8">No report found.</td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>