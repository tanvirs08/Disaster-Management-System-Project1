<?php
include "config/db.php";
include "includes/header.php";

$search = "";
$date = "";

$where = "WHERE 1";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND location LIKE '%$search%'";
}

if (isset($_GET['date']) && $_GET['date'] != "") {
    $date = mysqli_real_escape_string($conn, $_GET['date']);
    $where .= " AND DATE(recorded_at)='$date'";
}

$weather = mysqli_query($conn, "SELECT * FROM weather_data $where ORDER BY weather_id DESC LIMIT 100");
?>

<div class="dashboard">
    <h1>Weather History</h1>
    <p>Previous weather records saved from live weather API.</p>

    <form method="GET" class="filter-box">
        <input type="text" name="search" placeholder="Search location" value="<?php echo $search; ?>">
        <input type="date" name="date" value="<?php echo $date; ?>">
        <button type="submit" class="btn">Search</button>
        <a href="weather-history.php" class="btn reset-btn">Reset</a>
    </form>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Location</th>
            <th>Temperature</th>
            <th>Humidity</th>
            <th>Wind</th>
            <th>Pressure</th>
            <th>Rainfall</th>
            <th>Condition</th>
            <th>Recorded At</th>
        </tr>

        <?php if (mysqli_num_rows($weather) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($weather)) { ?>
                <tr>
                    <td><?php echo $row['weather_id']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['temperature']; ?> °C</td>
                    <td><?php echo $row['humidity']; ?>%</td>
                    <td><?php echo $row['wind_speed']; ?> km/h</td>
                    <td><?php echo $row['pressure']; ?> hPa</td>
                    <td><?php echo $row['rainfall']; ?> mm</td>
                    <td><?php echo $row['weather_condition']; ?></td>
                    <td><?php echo $row['recorded_at']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="9">No weather history found.</td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include "includes/footer.php"; ?>