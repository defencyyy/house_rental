<?php
session_start();
include('./db_connect.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate email format using regular expression
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        // Invalid email format
        echo "Invalid email format. Please enter a valid email address.";
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
            echo "Email already exists. Please use a different email.";
        } elseif ($result_username->num_rows > 0) {
            // Username already exists
            echo "Username already exists. Please choose a different username.";
        } elseif (strlen($password) < 8) {
            // Password length is less than 8 characters
            echo "Password must be at least 8 characters long.";
        } elseif (!preg_match("/[A-Z]/", $password)) {
            // Password does not contain an uppercase letter
            echo "Password must contain at least one uppercase letter.";
        } elseif (!preg_match("/[a-z]/", $password)) {
            // Password does not contain a lowercase letter
            echo "Password must contain at least one lowercase letter.";
        } elseif (!preg_match("/[0-9]/", $password)) {
            // Password does not contain a number
            echo "Password must contain at least one number.";
        } elseif (!preg_match("/[!@#$%^&*()-_=+{};:,<.>]/", $password)) {
            // Password does not contain a special character
            echo "Password must contain at least one special character.";
        } else {
            // No existing email or username, and password meets strength requirements, proceed with registration

            // Retrieve other form data
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];

            // Hash the password for better security
            $hashed_password = md5($password); // Note: Consider using stronger hashing algorithms like bcrypt

            // Prepare and bind parameters for the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO users (email, firstname, lastname, username, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $email, $firstname, $lastname, $username, $hashed_password);

            // Execute the prepared statement
            if ($stmt->execute()) {
                // Registration successful
                echo "Registration successful. You can now login.";
                // Redirect user to login page or any other page as needed
                // header("Location: login.php");
            } else {
                // Handle database error
                echo "Error: " . $conn->error;
            }

            // Close statement
            $stmt->close();
        }
    }
} else {
    // Redirect user to signup page if accessed directly
    header("Location: signup.php");
}
