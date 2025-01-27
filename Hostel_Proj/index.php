<?php
session_start();
include('php/db_connection.php');

// Initialize variables
$is_admin = false;
$is_logged_in = isset($_SESSION['user_id']);

// Check if the user is logged in and retrieve their admin status
if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $query = $conn->prepare("SELECT username, is_admin FROM users WHERE id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $query->bind_result($username, $is_admin);
    $query->fetch();
    $query->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTU Student Hostels</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
.open{
    display:block;
}
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <a href="index.php">
                <img src="images/logo.png" alt="TTU Logo">
            </a>
        </div>
        <nav>
            <ul class='menulist'>
                <li><a href="index.php">Home</a></li>
                <li><a href="#about-section">About Us</a></li>
                <li><a href="#gallery-section">Gallery</a></li>
                <li><a href="pages/bookings.php">Bookings</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="php/logout.php">Logout</a></li>
                    <li class="user-icon">
                        <a href="#">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?>
                        </a>
                    </li>
                    <?php if ($is_admin): ?>
                        <li><a href="pages/admin_dashboard.php">Manage</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="pages/signin.php">Sign In/Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="menu-icon closed">
            <i class="fas fa-bars" ></i>
        </div>
    </header>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="slider-container">
            <div class="slider">
                <img src="images/room1.jpg" alt="Room 1">
                <img src="images/room2.webp" alt="Room 2">
                <img src="images/room3.avif" alt="Room 3">
            </div>
            <div class="welcome-text">
                <h1>Welcome to TTU Student Hostels</h1>
                <p>Your ideal accommodation solution.</p>
                <a href="pages/bookings.php" class="cta-button">Explore Rooms</a>
            </div>
        </div>
    </section>


    <!-- About Section -->
    <section id="about-section">
        <h2>About Us</h2>
        <p>At TTU Student Hostels, we aim to provide comfortable, affordable, and safe accommodations for students. Our facilities include modern rooms, high-speed internet, a serene study environment, and proximity to the university campus.</p>
        <ul>
            <li><i class="fas fa-bed"></i> Comfortable, fully-furnished rooms</li>
            <li><i class="fas fa-wifi"></i> High-speed internet access</li>
            <li><i class="fas fa-shield-alt"></i> 24/7 security</li>
            <li><i class="fas fa-dollar-sign"></i> Affordable pricing</li>
        </ul>
    </section>

    <!-- Why Us Section -->
    <section id="why-us-section">
        <h2>Why Choose Us?</h2>
        <p>We go beyond just providing accommodation; we ensure that every student feels at home. Here’s what sets us apart:</p>
        <div class="why-us-items">
            <div class="why-us-item">
                <i class="fas fa-check-circle"></i>
                <div class="item-details">
                    <h3>Proximity to Campus</h3>
                    <p>Our hostels are located just minutes away from the campus, making it easy for students to attend classes, access libraries, and engage in university activities.</p>
                </div>
            </div>
            <div class="why-us-item">
                <i class="fas fa-cog"></i>
                <div class="item-details">
                    <h3>Modern Amenities</h3>
                    <p>We offer well-maintained, fully-furnished rooms with high-speed internet, study spaces, and recreational areas for a balanced lifestyle.</p>
                </div>
            </div>
            <div class="why-us-item">
                <i class="fas fa-heart"></i>
                <div class="item-details">
                    <h3>Dedicated Support</h3>
                    <p>Our staff is always available to support students with any needs or concerns, ensuring a smooth and comfortable living experience.</p>
                </div>
            </div>
           
        </div>
    </section>


    <!-- Gallery Section -->
    <section id="gallery-section">
        <h2>Gallery</h2>
        <div class="gallery-container">
            <div class="gallery-item">
                <img src="images/room1.jpg" alt="Room 1">
                <div class="gallery-caption">Room 1</div>
            </div>
            <div class="gallery-item">
                <img src="images/room2.webp" alt="Room 2">
                <div class="gallery-caption">Room 2</div>
            </div>
            <div class="gallery-item">
                <img src="images/room3.avif" alt="Room 3">
                <div class="gallery-caption">Room 3</div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact-section">
        <h2>Contact Us</h2>
        <div class="contact-details">
            <div class="detail-item">
                <i class="fas fa-envelope"></i>
                <p>Email: ttu.studenthostels@gmail.com</p>
            </div>
            <div class="detail-item">
                <i class="fas fa-phone"></i>
                <p>Phone: +233 59 115 4666</p>
            </div>
            <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <p>Town: Takoradi</p>
            </div>
            <div class="detail-item">
                <i class="fas fa-map-pin"></i>
                <p>GPS Address: TTU-123-4567</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> TTU Student Hostels. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript -->
    <script src="js/script.js"></script>
   
</body>
</html>
