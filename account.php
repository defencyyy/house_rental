<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['login_id'])) {
    header('location: homepage.php');
    exit();
}

if (isset($_POST['currentpassword']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $current_password = $_POST['currentpassword'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $username = $_SESSION['login_name'];

    // Retrieve the hashed password from the database
    $query = "SELECT password FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stored_password_hash = $row['password'];

        // Verify if the current password matches the one stored in the database
        if (md5($current_password) === $stored_password_hash) {
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

                    // Update the hashed password in the database
                    $update_query = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
                    if (mysqli_query($conn, $update_query)) {
                        echo "<script>alert('Password updated successfully.');</script>";
                    } else {
                        echo "<script>alert('Failed to update password. Please try again.');</script>";
                    }
                }
            }
        } else {
            echo "<script>alert('Current password is incorrect.');</script>";
        }
    } else {
        echo "<script>alert('Error fetching user data.');</script>";
    }
}
?>



<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?></title>
    <link rel="icon" type="image/png" href="assets\pictures\livwellLogo.png">


    <?php
    if (!isset($_SESSION['login_id']))
        header('location:login.php');
    include('./header.php');
    ?>
    <link rel="stylesheet" href="assets\css\styledash.css">
</head>


<body>
    <?php include 'topbar.php' ?>
    <?php include 'navbar.php' ?>
    <h2 class="title-account">Account</h2>
    <div class="account-banner">
        <div class="banner-content">
            <div class="profilepic"></div>
            <div class="account-name"><?php echo $_SESSION['login_name'] ?></div>
            <div class="account-status">Landlord</div>
            <div class="button-container">
                <button type="button" id="changeprofile">Change Profile Picture</button>
                <button type="submit" id="updateprofile">Update Profile</button>
            </div>
        </div>
    </div>
    <div class="information">
        <div class="box-information">
            <div class="boxcontent">
                <p class="title-box"> Personal Information </p>
                <p class="box-title"> Legal Name </p>
                <div class="line"></div>
                <p class="box-description"><?php echo $_SESSION['login_name'] ?>
            </div>
            <p class="box-title"> Date of Birth </p>
            <div class="line"></div>
            <p class="box-description">April 1, 2002
        </div>
        <p class="box-title1"> Phone Number </p>
        <div class="line1"></div>
        <p class="box-description1">0927025355
    </div>
    <p class="box-title1"> Email </p>
    <div class="line1"></div>
    <p class="box-description1">jewel@gmail.com</div>
    <p class="box-title1"> Address </p>
    <div class="line1"></div>
    <p class="box-description1">Ayala Blvd., Ermita Manila</div>
        </div>
        </div>
    <div class="box-information1">
        <div class="boxcontent">
            <p class="title-box">Security</p>
            <p class="box-title">Password</p>
            <div class="line"></div>
            <p class="box-description">Change your password</p>
            <form id="input-form-password" method="post">
                <div>
                    <p class="title-inputPass">Current Password</p>
                    <input type="password" name="currentpassword" placeholder="Enter your current password" class="custom-inputPass">
                </div>
                <div>
                    <p class="title-inputPass">New Password</p>
                    <input type="password" name="new_password" placeholder="Enter your new password" class="custom-inputPass">
                </div>
                <div>
                    <p class="title-inputPass">Confirm Password</p>
                    <input type="password" name="confirm_password" placeholder="Confirm your password" class="custom-inputPass">
                </div>
                <button type="submit" name="updatepass" id="updatepass">Update Password</button>
            </form>
        </div>
    </div>

    </div>
</body>