<?php
session_start();
include('./db_connect.php');
ob_start();
if (!isset($_SESSION['system'])) {
    $system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
    foreach ($system as $k => $v) {
        $_SESSION['system'][$k] = $v;
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_GET['email'])) {
    try {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        // Get the email from the URL
        $email = $_GET['email'];

        // Server settings
        $mail->isSMTP();                        // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';   // SMTP server
        $mail->SMTPAuth   = true;               // Enable SMTP authentication
        $mail->Username   = 'uyyn90786@gmail.com'; // SMTP username
        $mail->Password   = 'vnbovrgzflequvem';   // SMTP password
        $mail->SMTPSecure = 'ssl';              // Enable TLS encryption; `ssl` also accepted
        $mail->Port       = 465;                // TCP port to connect to

        // Recipients
        $mail->setFrom('uyyn90786@gmail.com'); // Sender email
        $mail->addAddress($email);             // Add recipient email  
        $mail->addReplyTo('uyyn90786@gmail.com'); // Reply to sender email

        // Content
        $mail->isHTML(true);                       // Set email format to HTML
        $mail->Subject = 'Password Reset Request'; // Email subject
        $mail->Body    = 'Dear user, 
            You have requested to reset your password. 
            Please follow the link below to reset your password: 
            https://www.youtube.com/watch?v=dQw4w9WgXcQ'; // Email message

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('Failed to send email: {$mail->ErrorInfo}');</script>";
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?php echo $_SESSION['system']['name'] = "LivWell"; ?></title>
    <link rel="icon" type="image/png" href="assets\pictures\livwellLogo.png">
    <link rel="stylesheet" href="assets\css\newstyle.css">
    <?php
    if (isset($_SESSION['login_id']))
        header("location:index.php?page=home");
    ?>
</head>

<body>
    <!-- ===== Navigation ===== -->
    <section id="header">
        <div class="nav-container">
            <nav id="desktop-nav">
                <img src="assets\pictures\livwellLogo.png" id="logoimg" />
                <div class="logoText">LivWell</div>
                <ul id="nav-links">
                    <li><a href="homepage.php">Home</a></li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- ===== Main ===== -->
    <section id="main">
        <div class="wrapper-forgot-2">
            <h2 id="title1Main"> Reset Password </h2>
            <div class="box">
                <p class="message">Check your email '<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>' for a link to reset your password. If it doesn’t appear within a few minutes, check your spam folder.</p>

            </div>
        </div>
    </section>

    <!-- ===== Footer ===== -->
    <footer>
        <p>Copyright &#169; 2024 LivWell: Apartment Management System. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginbtn-signup').click(function() {
                window.location.href = 'login.php';
            });
        });
    </script>
</body>