<?php
include "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $update = "UPDATE users 
               SET full_name='$full_name', phone='$phone', address='$address' 
               WHERE user_id='$user_id'";

    if (mysqli_query($conn, $update)) {
        $_SESSION['full_name'] = $full_name;
        $message = "<div class='message success'>Profile updated successfully!</div>";

        $user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'");
        $user = mysqli_fetch_assoc($user_query);
    } else {
        $message = "<div class='message error'>Failed to update profile.</div>";
    }
}
?>

<div class="dashboard">
    <h1>My Profile</h1>
    <p>View and update your account information.</p>

    <?php echo $message; ?>

    <div class="profile-layout">
        <div class="profile-card">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
            </div>

            <h2><?php echo $user['full_name']; ?></h2>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
            <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
            <p><strong>Status:</strong> 
                <span class="status-badge <?php echo strtolower($user['status']); ?>">
                    <?php echo $user['status']; ?>
                </span>
            </p>
            <p><strong>Joined:</strong> <?php echo $user['created_at']; ?></p>
        </div>

        <div class="form-container profile-form">
            <h2>Update Profile</h2>

            <form method="POST">
                <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" placeholder="Full Name" required>

                <input type="text" name="phone" value="<?php echo $user['phone']; ?>" placeholder="Phone Number">

                <textarea name="address" placeholder="Address"><?php echo $user['address']; ?></textarea>

                <button type="submit" name="update_profile" class="btn">Update Profile</button>
            </form>

            <br>
            <a href="change-password.php" class="btn">Change Password</a>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>