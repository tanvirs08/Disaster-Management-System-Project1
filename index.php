<?php
include __DIR__ . '/config/db.php';
include __DIR__ . '/includes/header.php';

$active_alerts_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM disaster_alerts WHERE status='Active'"))['total'];
$total_reports_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM disaster_reports WHERE status='Verified'"))['total'];
$total_shelters_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM shelter_centers WHERE status='Open'"))['total'];
$total_contacts_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM emergency_contacts WHERE status='Active'"))['total'];

$latest_alerts = mysqli_query($conn, "SELECT * FROM disaster_alerts WHERE status='Active' ORDER BY alert_id DESC LIMIT 3");
?>

<section class="home-hero">
    <div class="home-hero-content">
        <span class="hero-badge">AI-Based Disaster Management System</span>

        <h1>Stay Alert. Stay Safe. Get Real-Time Disaster Updates.</h1>

        <p>
            A smart disaster management platform with live weather forecast,
            AI-based risk prediction, emergency contacts, shelter information,
            and real-time public alert system.
        </p>

        <div class="hero-buttons">
            <a href="weather.php" class="btn">Check Weather</a>
            <a href="alerts.php" class="btn outline-btn">View Alerts</a>
            <a href="ai-prediction.php" class="btn">AI Prediction</a>
        </div>
    </div>
</section>

<section class="home-stats">
    <div class="stat-card">
        <h2><?php echo $active_alerts_count; ?></h2>
        <p>Active Alerts</p>
    </div>

    <div class="stat-card">
        <h2><?php echo $total_reports_count; ?></h2>
        <p>Verified Reports</p>
    </div>

    <div class="stat-card">
        <h2><?php echo $total_shelters_count; ?></h2>
        <p>Open Shelters</p>
    </div>

    <div class="stat-card">
        <h2><?php echo $total_contacts_count; ?></h2>
        <p>Emergency Contacts</p>
    </div>
</section>

<section class="home-section">
    <div class="section-title">
        <h2>Core System Features</h2>
        <p>Advanced features designed for disaster preparedness, response and public awareness.</p>
    </div>

    <div class="feature-grid">
        <div class="feature-card">
            <div class="feature-icon">🌦️</div>
            <h3>Live Weather</h3>
            <p>View real-time temperature, humidity, rainfall, wind speed and pressure.</p>
            <a href="weather.php">Explore Weather</a>
        </div>

        <div class="feature-card">
            <div class="feature-icon">📅</div>
            <h3>10 Days Forecast</h3>
            <p>Check upcoming weather forecast and rain possibility for Bangladesh districts.</p>
            <a href="forecast.php">View Forecast</a>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🤖</div>
            <h3>AI Risk Prediction</h3>
            <p>Predict flood, cyclone, storm or heatwave risk from weather parameters.</p>
            <a href="ai-prediction.php">Predict Risk</a>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🚨</div>
            <h3>Disaster Alerts</h3>
            <p>Receive admin-published disaster alerts with severity and safety instructions.</p>
            <a href="alerts.php">View Alerts</a>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🏠</div>
            <h3>Shelter Centers</h3>
            <p>Find nearby shelter centers with capacity, available space and facilities.</p>
            <a href="shelters.php">Find Shelters</a>
        </div>

        <div class="feature-card">
            <div class="feature-icon">📞</div>
            <h3>Emergency Contacts</h3>
            <p>Access important fire service, hospital, rescue and hotline contacts.</p>
            <a href="emergency.php">See Contacts</a>
        </div>
    </div>
</section>

<section class="home-section alert-preview-section">
    <div class="section-title">
        <h2>Latest Disaster Alerts</h2>
        <p>Recent active alerts published by the administration.</p>
    </div>

    <div class="home-alert-grid">
        <?php if (mysqli_num_rows($latest_alerts) > 0) { ?>
            <?php while ($alert = mysqli_fetch_assoc($latest_alerts)) { ?>
                <div class="home-alert-card">
                    <span class="alert-type"><?php echo $alert['alert_type']; ?></span>
                    <h3><?php echo $alert['title']; ?></h3>
                    <p><strong>Location:</strong> <?php echo $alert['location']; ?></p>
                    <p><strong>Severity:</strong> <?php echo $alert['severity']; ?></p>
                    <p><?php echo substr($alert['description'], 0, 120); ?>...</p>
                    <a href="alerts.php" class="small-btn info-btn">Read More</a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="message success">No active disaster alerts right now.</div>
        <?php } ?>
    </div>
</section>

<section class="emergency-cta">
    <div>
        <h2>Are you facing an emergency?</h2>
        <p>Submit a disaster report or check emergency contacts immediately.</p>
    </div>

    <div>
        <?php if(isset($_SESSION['user_id'])) { ?>
            <a href="report-disaster.php" class="btn">Report Disaster</a>
        <?php } else { ?>
            <a href="login.php" class="btn">Login to Report</a>
        <?php } ?>

        <a href="emergency.php" class="btn outline-btn">Emergency Contacts</a>
    </div>
</section>

<?php include "includes/footer.php"; ?>