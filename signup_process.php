<?php
session_start();
include('./db_connect.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        // Invalid email format
        echo "<script>alert('Invalid email format. Please enter a valid email address.');</script>";
        echo "<script>window.location.href = 'signup.php';</script>";
    } else {
        // Check if email exists
        $check_email_query = "SELECT * FROM users WHERE email = '$email'";
        $result_email = $conn->query($check_email_query);

        // Check if username exists
        $check_username_query = "SELECT * FROM users WHERE username = '$username'";
        $result_username = $conn->query($check_username_query);

        // Check if email or username already exists
        if ($result_email->num_rows > 0) {
            // Email already exists
            echo "<script>alert('Email already exists. Please use a different email.');</script>";
            echo "<script>window.location.href = 'signup.php';</script>";
        } elseif ($result_username->num_rows > 0) {
            // Username already exists
            echo "<script>alert('Username already exists. Please choose a different username.');</script>";
            echo "<script>window.location.href = 'signup.php';</script>";
        } elseif (strlen($password) < 8) {
            // Password length is less than 8 characters
            echo "<script>alert('Password must be at least 8 characters long.');</script>";
            echo "<script>window.location.href = 'signup.php';</script>";
        } elseif (!preg_match("/[!@#$%^&*()-_=+{};:,<.>]/", $password)) {
            // Password does not contain a special character
            echo "<script>alert('Password must contain at least one special character.');</script>";
            echo "<script>window.location.href = 'signup.php';</script>";
        } else {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];

            $hashed_password = md5($password);

            $stmt = $conn->prepare("INSERT INTO users (email, firstname, lastname, username, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $email, $firstname, $lastname, $username, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful. You can now login.');</script>";
                echo "<script>window.location.href = 'login.php';</script>";
                exit; 
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }

            $stmt->close();
        }
    }
}
?>
