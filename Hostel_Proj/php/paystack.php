<?php
session_start();
include('db_connection.php');

// Paystack Secret Key
$secret_key = "sk_test_09fbe149b20525947cb57fc6ac887d6d68868eb8";

// Check if form data is submitted
if (isset($_GET['room_id']) && isset($_GET['user_id']) && isset($_GET['check_in_date']) && isset($_GET['check_out_date']) && isset($_GET['reference'])) {
    $room_id = $_GET['room_id'];
    $user_id = $_GET['user_id'];
    $check_in_date = $_GET['check_in_date'];
    $check_out_date = $_GET['check_out_date'];
    $reference = $_GET['reference'];
    
    // Get user details from the database
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $user_email = $user['email']; // email is stored in the database

    // Get room details
    $sql = "SELECT * FROM rooms WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    $amount = $room['price']; // Amount in GHS

    // Check if room is available
    if ($room['booked_count'] >= $room['capacity']) {
        echo "This room is fully booked.";
        exit;
    }

    // Prepare the Paystack request to create a transaction
    $url = "https://api.paystack.co/transaction/initialize";
    $fields = [
        'email' => $user_email,
        'amount' => $amount * 100, // Paystack expects the amount in kobo (i.e., GHS * 100)
        'reference' => $reference,
        'callback_url' => 'http://localhost/Hostel_Proj/php/payment_callback.php?reference=' . $reference . '&room_id=' . $room_id
    ];

    $fields_string = http_build_query($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $secret_key",
        "Content-Type: application/x-www-form-urlencoded"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response
    $response_data = json_decode($response, true);

    if ($response_data['status'] == true) {
        // Redirect to Paystack payment page
        header("Location: " . $response_data['data']['authorization_url']);
        exit;
    } else {
        echo "Payment initialization failed. Please try again.";
    }
} else {
    echo "Invalid payment request.";
}
?>
