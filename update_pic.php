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
        <div class = "box-information">
            <p class = "title-box"> Personal Information</p>
            <div class = "boxcontent1">
                <p class = "box-title1">Current Name</p>
                <input type="text" name="login_name" class="custom-inputPass" placeholder=<?php echo $_SESSION['login_name']?>>
                <p class = "box-title1">Current Date of Birth</p>
                <input type="text" name="login_name" class="custom-inputPass" placeholder=<?php echo $_SESSION['login_name']?> class = holder>
                <p class = "box-title1">Current Phone Number</p>
                <input type="text" name="login_name" class="custom-inputPass" placeholder=<?php echo $_SESSION['login_name']?> class = holder>
                <p class = "box-title1">Current Email Address</p>
                <input type="text" name="login_name" class="custom-inputPass" placeholder=<?php echo $_SESSION['login_name']?> class = holder>
                <p class = "box-title1">Current Address</p>
                <input type="text" name="login_name" class="custom-inputPass" placeholder=<?php echo $_SESSION['login_name']?>>
                <br><br><br>
                <p class = "box-title1">Profile Picture</p>
                
                <!-- <br><br><div class = "profilepic"></div><br><br><br> -->

                <span class = circle></span> <br> <br>
                
                <button type="file" name="login_name" ><input type="file" placeholder="Name"></button>
                
                <br><button id="updatepass">Update Information</button>
                <!-- <p class = "box-description1"><?php echo $_SESSION['login_name']?></p> -->
            </div>
        </div>
        
    </div>
</body>