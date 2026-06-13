<?php
include "config/db.php";
include "includes/header.php";

$search = "";
$status = "";

$where = "WHERE 1";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND (shelter_name LIKE '%$search%' OR location LIKE '%$search%' OR facilities LIKE '%$search%')";
}

if (isset($_GET['status']) && $_GET['status'] != "") {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $where .= " AND status='$status'";
}

$shelters = mysqli_query($conn, "SELECT * FROM shelter_centers $where ORDER BY shelter_id DESC");
?>

<div class="dashboard">
    <h1>Emergency Shelter Centers</h1>
    <p>Search shelters by location, facility and status.</p>

    <form method="GET" class="filter-box">
        <input type="text" name="search" placeholder="Search shelter, location, facilities" value="<?php echo $search; ?>">

        <select name="status">
            <option value="">All Status</option>
            <option value="Open" <?php if($status=="Open") echo "selected"; ?>>Open</option>
            <option value="Closed" <?php if($status=="Closed") echo "selected"; ?>>Closed</option>
            <option value="Full" <?php if($status=="Full") echo "selected"; ?>>Full</option>
        </select>

        <button type="submit" class="btn">Filter</button>
        <a href="shelters.php" class="btn reset-btn">Reset</a>
    </form>

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
            <th>Status</th>
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
                        <span class="status-badge <?php echo strtolower($row['status']); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="9">No shelter center found.</td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include "includes/footer.php"; ?>