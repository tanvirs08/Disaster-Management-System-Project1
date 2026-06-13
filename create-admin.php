<?php
include "config/db.php";

$name = "Super Admin";
$email = "admin@gmail.com";
$plain_password = "123456";
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
$role = "super_admin";

$delete_sql = "DELETE FROM admins WHERE email = '$email'";
mysqli_query($conn, $delete_sql);

$insert_sql = "INSERT INTO admins (name, email, password, role)
               VALUES ('$name', '$email', '$hashed_password', '$role')";

if (mysqli_query($conn, $insert_sql)) {
    echo "<h2>Admin created successfully!</h2>";
    echo "<p>Email: admin@gmail.com</p>";
    echo "<p>Password: 123456</p>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>