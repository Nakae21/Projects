<?php
session_start();
include('db_connection.php');

// Check if reference and room_id are provided
if (!isset($_GET['reference']) || !isset($_GET['room_id'])) {
    die("Invalid access.");
}

$reference = $_GET['reference'];
$room_id = $_GET['room_id'];

// Paystack Secret Key
$secret_key = "sk_test_09fbe149b20525947cb57fc6ac887d6d68868eb8";

// Initialize cURL for Paystack verification
$url = "https://api.paystack.co/transaction/verify/" . $reference;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $secret_key"
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

// Check if the payment was successful
if ($result['status'] && $result['data']['status'] === 'success') {
    // Payment details
    $amount_paid = $result['data']['amount'] / 100; // Convert to GHS
    $email = $result['data']['customer']['email'];
    $user_name = $result['data']['customer']['first_name'] . ' ' . $result['data']['customer']['last_name']; // Use customer's name if available

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("User is not logged in.");
    }

    $user_id = $_SESSION['user_id'];  // Assuming the user is logged in and their ID is stored in session
    $check_in_date = date("Y-m-d H:i:s");
    $check_out_date = date("Y-m-d H:i:s", strtotime("+1 day")); // Example: Set check-out as the next day
    $status = "Paid";
    $created_at = date("Y-m-d H:i:s"); // Current timestamp

    // Insert booking into database
    $stmt = $conn->prepare("INSERT INTO user_bookings (user_id, room_id, user_name, user_email, check_in_date, check_out_date, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt->bind_param("iissssss", $user_id, $room_id, $user_name, $email, $check_in_date, $check_out_date, $status, $created_at);

    if ($stmt->execute()) {
        // Update booked count and check availability
        $update_booked_count = "UPDATE rooms SET booked_count = booked_count + 1 WHERE id = ?";
        $stmt_update = $conn->prepare($update_booked_count);
        $stmt_update->bind_param("i", $room_id);
        $stmt_update->execute();

        // If the room is fully booked, set availability to 0
        $update_availability = "UPDATE rooms SET availability = 0 WHERE id = ? AND booked_count >= capacity";
        $stmt_update_availability = $conn->prepare($update_availability);
        $stmt_update_availability->bind_param("i", $room_id);
        $stmt_update_availability->execute();

        // Redirect to the bookings page
        echo "<script>
            alert('Payment successful! Your booking has been confirmed.');
            window.location.href = '../pages/bookings.php';
        </script>";
    } else {
        // Error executing the query
        die('Execute error: ' . $stmt->error);
    }
    $stmt->close();
} else {
    // Payment failed
    echo "<script>
        alert('Payment failed! Please try again.');
        window.location.href = '../pages/bookings.php';
    </script>";
}

$conn->close();
?>
