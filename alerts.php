<?php
include "config/db.php";
include "includes/header.php";

$search = "";
$type = "";
$severity = "";

$where = "WHERE status='Active'";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND (title LIKE '%$search%' OR location LIKE '%$search%' OR description LIKE '%$search%')";
}

if (isset($_GET['type']) && $_GET['type'] != "") {
    $type = mysqli_real_escape_string($conn, $_GET['type']);
    $where .= " AND alert_type='$type'";
}

if (isset($_GET['severity']) && $_GET['severity'] != "") {
    $severity = mysqli_real_escape_string($conn, $_GET['severity']);
    $where .= " AND severity='$severity'";
}

$alerts = mysqli_query($conn, "SELECT * FROM disaster_alerts $where ORDER BY alert_id DESC");
?>

<div class="dashboard">
    <h1>Active Disaster Alerts</h1>
    <p>Search and filter latest disaster warnings.</p>

    <form method="GET" class="filter-box">
        <input type="text" name="search" placeholder="Search by title, location, description" value="<?php echo $search; ?>">

        <select name="type">
            <option value="">All Types</option>
            <option value="Cyclone" <?php if($type=="Cyclone") echo "selected"; ?>>Cyclone</option>
            <option value="Flood" <?php if($type=="Flood") echo "selected"; ?>>Flood</option>
            <option value="Heavy Rain" <?php if($type=="Heavy Rain") echo "selected"; ?>>Heavy Rain</option>
            <option value="Heatwave" <?php if($type=="Heatwave") echo "selected"; ?>>Heatwave</option>
            <option value="Thunderstorm" <?php if($type=="Thunderstorm") echo "selected"; ?>>Thunderstorm</option>
            <option value="Earthquake" <?php if($type=="Earthquake") echo "selected"; ?>>Earthquake</option>
            <option value="Landslide" <?php if($type=="Landslide") echo "selected"; ?>>Landslide</option>
            <option value="Other" <?php if($type=="Other") echo "selected"; ?>>Other</option>
        </select>

        <select name="severity">
            <option value="">All Severity</option>
            <option value="Low" <?php if($severity=="Low") echo "selected"; ?>>Low</option>
            <option value="Medium" <?php if($severity=="Medium") echo "selected"; ?>>Medium</option>
            <option value="High" <?php if($severity=="High") echo "selected"; ?>>High</option>
            <option value="Critical" <?php if($severity=="Critical") echo "selected"; ?>>Critical</option>
        </select>

        <button type="submit" class="btn">Filter</button>
        <a href="alerts.php" class="btn reset-btn">Reset</a>
    </form>

    <div class="alert-list">
        <?php if (mysqli_num_rows($alerts) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($alerts)) { ?>
                <div class="alert-card">
                    <h2><?php echo $row['title']; ?></h2>
                    <p><strong>Type:</strong> <?php echo $row['alert_type']; ?></p>
                    <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                    <p><strong>Severity:</strong> <span class="severity"><?php echo $row['severity']; ?></span></p>
                    <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                    <p><strong>Instruction:</strong> <?php echo $row['instruction']; ?></p>
                    <p><strong>Issued At:</strong> <?php echo $row['issued_at']; ?></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="message error">No alert found.</div>
        <?php } ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>