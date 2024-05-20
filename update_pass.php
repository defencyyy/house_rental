<!DOCTYPE html>
<html lang="en">
	
<?php session_start();
if(!isset($_SESSION['login_id'])) {
    header('location: homepage.php');
    exit();
}
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?></title>
 	<link rel="icon" type="image/png" href="assets\pictures\livwellLogo.png">


  <?php
    if(!isset($_SESSION['login_id']))
      header('location:login.php');
    include('./header.php'); 
  ?>
  <link rel="stylesheet" href="assets\css\styledash.css">
</head>
<?php include('db_connect.php');?>
<body id = "bods">
	<?php include 'topbar.php' ?>
	<?php include 'navbar.php' ?>
    <section id = "accounthead">
    <h2 class = "title-account">Update Profile</h2>
    <div class = "account-banner">
        <div class = "banner-content">
            <div class = "profilepic"></div>
            <div class = "account-name"><?php echo $_SESSION['login_name']?></div>
            <div class = "account-status">Landlord</div>
            <div class="button-container">
                
                <button type="button" id="changeprofile">Update Information</button>
                <button type="submit" id="updateprofile">Change Password</button>
                
            </div>
        </div>
    </div>
    </section>
    <div class = "information">
        <div class = "box-information1">
            <div class = "boxcontent1">
                <p class = "title-box">Security</p>
                <!-- <p class = "box-title">Password</p> -->
                
                <p class = "box-description">Change your password</p>
                <form id = "input-form-password">
                    <div>
                        <p class = "title-inputPass">Current Password</p>
                        <input type="password" name="currentpassword" placeholder="Enter your current password" class="custom-inputPass">
                    </div>
                    <div>
                        <p class = "title-inputPass">New Password</p>
                        <input type="password" name="newpassword" placeholder="Enter your new password" class="custom-inputPass">
                    </div>
                    <div>
                        <p class = "title-inputPass">Current Password</p>
                        <input type="password" name="confirmpassword" placeholder="Confirm your password" class="custom-inputPass">
                    </div>
                    
                </form>
                <button type="submit" id="updatepass" >Update Password</button>
            </div>
        </div>
        <!-- <div class = "box-information2">
            <div class = "boxcontent">
                <p>Update Profile</p>
                <button type = "submit">Choose File <input type="text" placeholder = "No file chosen"></button>
            </div>
        </div> -->

    </div>
</body>