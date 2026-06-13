<?php
include "config/db.php";
include "includes/header.php";

$contacts = mysqli_query($conn, "SELECT * FROM emergency_contacts WHERE status='Active' ORDER BY contact_id DESC");
?>

<div class="dashboard">
    <h1>Emergency Contacts</h1>
    <p>Important emergency service contacts for disaster situations.</p>

    <div class="report-grid">
        <?php if (mysqli_num_rows($contacts) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($contacts)) { ?>
                <div class="report-card">
                    <div class="report-card-content">
                        <h2><?php echo $row['service_name']; ?></h2>
                        <p><strong>Type:</strong> <?php echo $row['service_type']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>
                        <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                        <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                        <p><?php echo $row['description']; ?></p>
                        <span class="status-badge verified">Active</span>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="message error">No emergency contacts available.</div>
        <?php } ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>