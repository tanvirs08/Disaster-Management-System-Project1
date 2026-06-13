<?php
include "config/db.php";
include "includes/header.php";

$search = "";
$type = "";

$where = "WHERE disaster_reports.status='Verified'";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND (disaster_reports.location LIKE '%$search%' OR disaster_reports.description LIKE '%$search%' OR users.full_name LIKE '%$search%')";
}

if (isset($_GET['type']) && $_GET['type'] != "") {
    $type = mysqli_real_escape_string($conn, $_GET['type']);
    $where .= " AND disaster_reports.disaster_type='$type'";
}

$reports = mysqli_query($conn, "SELECT disaster_reports.*, users.full_name 
                                FROM disaster_reports 
                                LEFT JOIN users ON disaster_reports.user_id = users.user_id
                                $where
                                ORDER BY disaster_reports.report_id DESC");
?>

<div class="dashboard">
    <h1>Verified Disaster Reports</h1>
    <p>Search verified reports from affected areas.</p>

    <form method="GET" class="filter-box">
        <input type="text" name="search" placeholder="Search by location, user, description" value="<?php echo $search; ?>">

        <select name="type">
            <option value="">All Types</option>
            <option value="Flood" <?php if($type=="Flood") echo "selected"; ?>>Flood</option>
            <option value="Cyclone" <?php if($type=="Cyclone") echo "selected"; ?>>Cyclone</option>
            <option value="Heavy Rain" <?php if($type=="Heavy Rain") echo "selected"; ?>>Heavy Rain</option>
            <option value="Fire" <?php if($type=="Fire") echo "selected"; ?>>Fire</option>
            <option value="Earthquake" <?php if($type=="Earthquake") echo "selected"; ?>>Earthquake</option>
            <option value="Landslide" <?php if($type=="Landslide") echo "selected"; ?>>Landslide</option>
            <option value="Road Block" <?php if($type=="Road Block") echo "selected"; ?>>Road Block</option>
            <option value="Other" <?php if($type=="Other") echo "selected"; ?>>Other</option>
        </select>

        <button type="submit" class="btn">Search</button>
        <a href="verified-reports.php" class="btn reset-btn">Reset</a>
    </form>

    <div class="report-grid">
        <?php if (mysqli_num_rows($reports) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($reports)) { ?>
                <div class="report-card">
                    <?php if (!empty($row['image'])) { ?>
                        <img src="assets/uploads/<?php echo $row['image']; ?>" alt="Report Image">
                    <?php } ?>

                    <div class="report-card-content">
                        <h2><?php echo $row['disaster_type']; ?></h2>
                        <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                        <p><strong>Reported By:</strong> <?php echo $row['full_name']; ?></p>
                        <p><?php echo $row['description']; ?></p>
                        <p><strong>Reported At:</strong> <?php echo $row['reported_at']; ?></p>
                        <span class="status-badge verified">Verified</span>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="message error">No verified report found.</div>
        <?php } ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>