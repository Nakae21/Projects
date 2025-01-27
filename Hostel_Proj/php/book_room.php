<?php
session_start();
include('../php/db_connection.php');

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'] ?? '';
$user_name = $_SESSION['user_name'] ?? '';

// Check if room_id is set in the URL
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    // Fetch room details based on the room_id
    $sql = "SELECT id, room_name, price, capacity, description FROM rooms WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    // Check if the room exists
    if (!$room) {
        echo 'Room not found.';
        exit;
    }

    // Handle booking form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $check_in_date = $_POST['check_in_date'];
        $check_out_date = $_POST['check_out_date'];

        // Generate a unique reference for the payment
        $reference = uniqid('pay_');

        // Redirect to Paystack payment page
        header("Location: ../php/paystack.php?room_id=$room_id&user_id=$user_id&check_in_date=$check_in_date&check_out_date=$check_out_date&reference=$reference");
        exit;
    }
} else {
    echo 'No room selected.';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room - TTU Student Hostels</title>
    <link rel="stylesheet" href="../css/home.css">
    <style>
        /* Booking page specific styles */
        .booking-form {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
        }

        .booking-form h2 {
            margin-bottom: 20px;
        }

        .booking-form input, .booking-form select, .booking-form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .booking-form button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .booking-form button:hover {
            background-color: #0056b3;
        }

        /* Footer Styling */
        footer {
            background-color: #003e47;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        body {
            margin-bottom: 60px; /* Ensure there's space for the fixed footer */
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo">
            <a href="../index.php">
                <img src="../images/logo.png" alt="TTU Logo" style="height: 40px;">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../pages/bookings.php">Bookings</a></li>
                <li><a href="../php/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Booking Form -->
    <section class="booking-form">
        <h2>Book Room: <?php echo htmlspecialchars($room['room_name']); ?></h2>
        <form action="" method="POST">
            <label for="check_in_date">Check-in Date</label>
            <input type="date" id="check_in_date" name="check_in_date" required min="<?php echo date('Y-m-d'); ?>" />

            <label for="check_out_date">Check-out Date</label>
            <input type="date" id="check_out_date" name="check_out_date" required />

            <button type="submit">Proceed to Payment</button>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> TTU Student Hostels. All Rights Reserved.</p>
    </footer>

    <script>
        // Set the default check-out date to 1 year after the check-in date
        document.getElementById("check_in_date").addEventListener("change", function() {
            const checkInDate = new Date(this.value);
            checkInDate.setFullYear(checkInDate.getFullYear() + 1);
            
            const checkOutDate = document.getElementById("check_out_date");
            checkOutDate.value = checkInDate.toISOString().split('T')[0]; // Set the check-out date
        });
    </script>

</body>
</html>
