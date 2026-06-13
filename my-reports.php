<?php
include "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$reports = mysqli_query($conn, "SELECT * FROM disaster_reports WHERE user_id='$user_id' ORDER BY report_id DESC");
?>

<div class="dashboard">
    <h1>My Submitted Reports</h1>
    <p>Track your submitted disaster reports and verification status.</p>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Location</th>
            <th>Description</th>
            <th>Image</th>
            <th>Status</th>
            <th>Admin Note</th>
            <th>Reported At</th>
        </tr>

        <?php if (mysqli_num_rows($reports) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($reports)) { ?>
                <tr>
                    <td><?php echo $row['report_id']; ?></td>
                    <td><?php echo $row['disaster_type']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <?php if (!empty($row['image'])) { ?>
                            <img src="assets/uploads/<?php echo $row['image']; ?>" width="80" height="60" style="object-fit:cover; border-radius:6px;">
                        <?php } else { ?>
                            No Image
                        <?php } ?>
                    </td>
                    <td>
                        <span class="status-badge <?php echo strtolower($row['status']); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td><?php echo $row['admin_note']; ?></td>
                    <td><?php echo $row['reported_at']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="8">You have not submitted any report yet.</td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include "includes/footer.php"; ?>