<?php
include "config/db.php";
include "includes/header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    $message = "<div class='message success'>Thank you, $name. Your message has been received successfully.</div>";
}
?>

<div class="dashboard">
    <h1>Contact Us</h1>
    <p>Send your message or feedback about the disaster management platform.</p>

    <div class="profile-layout">
        <div class="analytics-box">
            <h2>System Contact Information</h2>
            <p><strong>Project:</strong> AI-Based Disaster Management System</p>
            <p><strong>Email:</strong> support@disaster-system.local</p>
            <p><strong>Emergency Hotline:</strong> 999</p>
            <p><strong>Location:</strong> Bangladesh</p>

            <br>

            <h2>Important Note</h2>
            <p>
                For real emergency situations, please contact national emergency service immediately.
                This system is developed for academic project and demonstration purposes.
            </p>
        </div>

        <div class="form-container profile-form">
            <h2>Send Message</h2>

            <?php echo $message; ?>

            <form method="POST">
                <input type="text" name="name" placeholder="Your Name" required>

                <input type="email" name="email" placeholder="Your Email" required>

                <input type="text" name="subject" placeholder="Subject" required>

                <textarea name="message" placeholder="Your Message" required></textarea>

                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>