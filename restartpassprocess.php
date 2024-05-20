<?php
session_start();
include('./db_connect.php');

if (!isset($_GET['email']) || empty($_GET['email'])) {
    header("Location: homepage.php");
    exit();
}

$email = urldecode($_GET['email']);

if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if new password and confirm password match
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Add additional password verifications here if needed
        if (strlen($new_password) < 8) {
            echo "<script>alert('Password must be at least 8 characters long.');</script>";
        } elseif (!preg_match("/[A-Z]/", $new_password)) {
            echo "<script>alert('Password must contain at least one uppercase letter.');</script>";
        } elseif (!preg_match("/[a-z]/", $new_password)) {
            echo "<script>alert('Password must contain at least one lowercase letter.');</script>";
        } elseif (!preg_match("/[0-9]/", $new_password)) {
            echo "<script>alert('Password must contain at least one number.');</script>";
        } elseif (!preg_match("/[!@#$%^&*()-_=+{};:,<.>]/", $new_password)) {
            echo "<script>alert('Password must contain at least one special character.');</script>";
        } else {
            // Hash the new password
            $hashed_password = md5($new_password);

            // Update the password in the database
            $update_query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
            if (mysqli_query($conn, $update_query)) {
                echo "<script>alert('Password updated successfully.');</script>";
                echo "<script>setTimeout(function() { window.location.href = 'login.php'; });</script>";
            
            } else {
                echo "<script>alert('Failed to update password. Please try again.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Reset Password - LivWell</title>
    <link rel="icon" type="image/png" href="assets/pictures/livwellLogo.png">
    <link rel="stylesheet" href="assets/css/newstyle.css">
</head>

<body>
    <!-- ===== Navigation ===== -->
    <section id="header">
        <div class="nav-container">
            <nav id="desktop-nav">
                <img src="assets/pictures/livwellLogo.png" id="logoimg" />
                <div class="logoText">LivWell</div>
                <ul id="nav-links">
                    <li><a href="homepage.php">Home</a></li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- ===== Main ===== -->
    <section id="main">
        <div class="wrapper-forgot">
            <h2 id="title1Main"> Reset Password </h2>
            <form id="input-form-forgot" method="post">
                <div>
                    <h4 class="title-inputMain">New Password</h4>
                    <input type="password" name="new_password" placeholder="Enter your new password" class="custom-inputMain" required>
                </div>
                <div>
                    <h4 class="title-inputMain">Confirm Password</h4>
                    <input type="password" name="confirm_password" placeholder="Confirm your new password" class="custom-inputMain" required>
                </div>
                <div class="button-container">
                    <button type="submit" id="submitbtn">Submit</button>
                </div>
            </form>
        </div>
    </section>

    <!-- ===== Footer ===== -->
    <footer>
        <p>Copyright &#169; 2024 LivWell: Apartment Management System. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>