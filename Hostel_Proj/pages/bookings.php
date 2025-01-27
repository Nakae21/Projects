<?php
session_start();
include('../php/db_connection.php');

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';  // Get email from session

// Fetch available rooms
$sql = "SELECT id, room_name, description, price, capacity, image, availability, booked_count FROM rooms ORDER BY id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - TTU Student Hostels</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Booking page specific styles */
        .room-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .room-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .room-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .room-card .details {
            padding: 15px;
        }
        .room-card h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }
        .room-card p {
            margin: 0 0 10px;
            color: #555;
        }
        .room-card .price {
            font-weight: bold;
            color: #007bff;
        }
        .room-card .book-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .room-card .book-btn:hover {
            background: #0056b3;
        }
        .room-card.unavailable {
            background-color: #d3d3d3;
            opacity: 0.6;
        }
        .room-card.unavailable .book-btn {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <a href="../index.php">
                <img src="../images/logo.png" alt="TTU Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../pages/bookings.php">Bookings</a></li>
                <li><a href="../php/logout.php">Logout</a></li>
            </ul>
        </nav>
        <div class="menu-icon closed">
            <i class="fas fa-bars" ></i>
        </div>
    </header>

    <!-- Booking Content -->
    <section>
        <h1>Available Rooms</h1>
        <div class="room-container">
            <?php while ($row = $result->fetch_assoc()):
                // Calculate remaining capacity
                $remainingCapacity = $row['capacity'] - $row['booked_count']; 
                ?>
                <div class="room-card <?php echo ($remainingCapacity <= 0) ? 'unavailable' : ''; ?>">
                    <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['room_name']); ?>">
                    <div class="details">
                        <h3><?php echo htmlspecialchars($row['room_name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p>Capacity: <?php echo htmlspecialchars($row['capacity']); ?> people</p>
                        <p>Available: <?php echo $remainingCapacity; ?> left</p>
                        <p class="price">Price: ₵<?php echo number_format(htmlspecialchars($row['price']), 2); ?> per year</p>
                        <?php if ($remainingCapacity > 0): ?>
                            <a href="../php/book_room.php?room_id=<?php echo $row['id']; ?>" class="book-btn">Book Now</a>
                        <?php else: ?>
                            <button class="book-btn" disabled>Fully Booked</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> TTU Student Hostels. All Rights Reserved.</p>
    </footer>
      <!-- JavaScript -->
      <script src="../js/script.js"></script>
   
</body>
</html>
