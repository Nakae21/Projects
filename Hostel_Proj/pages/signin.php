<?php
session_start();
include('../php/db_connection.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user details from the database, including status and is_admin
    $sql = "SELECT id, email, password, is_admin, status FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the user is blocked
        if ($user['status'] == 'blocked') {
            $error = "Your account is blocked/deactivated, please contact administrator at ttu.studenthostel@gmail.com.";
        } else {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin']; // Store admin status in the session

                // Redirect based on user role
                if ($user['is_admin'] == 1) {
                    header("Location: ../pages/admin_dashboard.php"); // Redirect to admin dashboard
                } else {
                    header("Location: ../pages/bookings.php"); // Redirect to booking page
                }
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - TTU Student Hostels</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/account.css">
</head>

<body>
    <!-- Navigation Bar -->
    <header>
        <div class="logo">
            <a href="../index.php">
                <img src="../images/logo.png" alt="TTU Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../index.php#about">About Us</a></li>
                <li><a href="../index.php#gallery">Gallery</a></li>
                <li><a href="bookings.php">Bookings</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <!-- Sign-In Form -->
    <section>
        <form method="POST" action="">
            <h2>Sign In</h2>

            <!-- Display Error Messages -->
            <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit">Sign In</button>

            <div class="form-links">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 TTU Student Hostels. All Rights Reserved.</p>
    </footer>

    <script src="../js/script.js"></script>
</body>

</html>
