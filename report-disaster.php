<?php
include "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $disaster_type = mysqli_real_escape_string($conn, $_POST['disaster_type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $image_name = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_ext)) {
            $message = "<div class='message error'>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
        } elseif ($file_size > 2 * 1024 * 1024) {
            $message = "<div class='message error'>Image size must be less than 2MB.</div>";
        } else {
            $image_name = "report_" . time() . "_" . rand(1000, 9999) . "." . $file_ext;
            $upload_path = "assets/uploads/" . $image_name;

            move_uploaded_file($file_tmp, $upload_path);
        }
    }

    if ($message == "") {
        $sql = "INSERT INTO disaster_reports 
                (user_id, disaster_type, location, description, image, status)
                VALUES 
                ('$user_id', '$disaster_type', '$location', '$description', '$image_name', 'Pending')";

        if (mysqli_query($conn, $sql)) {
            $message = "<div class='message success'>Your disaster report has been submitted successfully. Admin will verify it.</div>";
        } else {
            $message = "<div class='message error'>Failed to submit report.</div>";
        }
    }
}
?>

<div class="dashboard">
    <h1>Report a Disaster</h1>
    <p>Submit disaster information from your area. Admin will verify your report.</p>

    <div class="form-container">
        <h2>Disaster Report Form</h2>

        <?php echo $message; ?>

        <form method="POST" enctype="multipart/form-data">
            <select name="disaster_type" required>
                <option value="">Select Disaster Type</option>
                <option value="Flood">Flood</option>
                <option value="Cyclone">Cyclone</option>
                <option value="Heavy Rain">Heavy Rain</option>
                <option value="Fire">Fire</option>
                <option value="Earthquake">Earthquake</option>
                <option value="Landslide">Landslide</option>
                <option value="Road Block">Road Block</option>
                <option value="Other">Other</option>
            </select>

<input type="text" name="location" list="districtList" placeholder="Select or type district" required>
<?php include "includes/districts.php"; ?>
            <textarea name="description" placeholder="Describe the situation..." required></textarea>

            <label>Upload Image:</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit" class="btn">Submit Report</button>
        </form>
    </div>
</div>

<?php include "includes/footer.php"; ?>