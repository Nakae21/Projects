<?php
// Start a session
session_start();

// Include database connection
include('db_connection.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Input validation
    if ($password != $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match.";
        header("Location: ../pages/signup.php");
        exit;
    }

    // Sanitize inputs
    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email is already registered
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        $_SESSION['error_message'] = "Email is already registered.";
        $checkEmail->close();
        header("Location: ../pages/signup.php");
        exit;
    }

    // Insert the user into the database (default is_admin = 0)
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Account created successfully! You can now log in.";
        header("Location: ../pages/signin.php");
    } else {
        $_SESSION['error_message'] = "Error creating account. Please try again later.";
    }

    // Close connections
    $stmt->close();
    $checkEmail->close();
    $conn->close();
} else {
    // Redirect if accessed without POST
    header("Location: ../pages/signup.php");
    exit;
}
?>
