<?php
// Start a session to show error or success messages
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - TTU Student Hostels</title>
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
                <li><a href="signin.php">Sign In</a></li>
            </ul>
        </nav>
    </header>

    <!-- Sign-Up Form -->
    <section>
        <form method="POST" action="../php/signup_handler.php">
            <h2>Sign Up</h2>

            <!-- Display Error or Success Messages -->
            <?php
            if (isset($_SESSION['error_message'])) {
                echo "<p class='error-message'>" . $_SESSION['error_message'] . "</p>";
                unset($_SESSION['error_message']);
            }
            if (isset($_SESSION['success_message'])) {
                echo "<p class='success-message'>" . $_SESSION['success_message'] . "</p>";
                unset($_SESSION['success_message']);
            }
            ?>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>

            <button type="submit">Sign Up</button>

            <div class="form-links">
                <p>Already have an account? <a href="signin.php">Sign In</a></p>
            </div>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 TTU Student Hostels. All Rights Reserved.</p>
    </footer>
</body>

</html>
