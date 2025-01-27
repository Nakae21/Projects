<?php
session_start();
include('../php/db_connection.php');

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($is_admin);
$query->fetch();
$query->close();

if (!$is_admin) {
    echo "Access denied. Only administrators can add rooms.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_name = $_POST['room_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $image = $_FILES['image']['name'];

    // Validate inputs
    if (empty($room_name) || empty($description) || empty($price) || empty($capacity) || empty($image)) {
        $error = "All fields are required.";
    } else {
        // Image upload path
        $target_dir = "../images/";
        $target_file = $target_dir . basename($image);

        // Insert into the database
        $query = $conn->prepare("INSERT INTO rooms (room_name, description, price, capacity, image) VALUES (?, ?, ?, ?, ?)");
        if ($query) {
            $query->bind_param("ssdss", $room_name, $description, $price, $capacity, $image);
            if ($query->execute()) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $success = "Room added successfully.";
                } else {
                    $error = "Failed to upload the image.";
                }
            } else {
                $error = "Database error: " . $query->error;
            }
            $query->close();
        } else {
            $error = "Query preparation failed: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <link rel="stylesheet" href="../css/home.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="file"] {
            margin-top: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            font-size: 14px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<header>
        <div class="logo">
            <a href="../index.php">
                <img src="../images/logo.png" alt="TTU Logo">
            </a>
        </div>
        <nav>
            <ul class='menulist'>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../index.php#about-section">About Us</a></li>
                <li><a href="../index.php#gallery-section">Gallery</a></li>
                <li><a href="../pages/bookings.php">Bookings</a></li>
               
            </ul>
        </nav>
        <div class="menu-icon closed">
            <i class="fas fa-bars" ></i>
        </div>
    </header>

    <div class="container">
        <h1>Add Room</h1>
        <?php if (isset($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="add_room.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="room_name">Room Name</label>
                <input type="text" id="room_name" name="room_name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price (per year)</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" required>
            </div>
            <div class="form-group">
                <label for="image">Room Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn">Add Room</button>
        </form>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> TTU Student Hostels. All Rights Reserved.</p>
    </footer>

</body>
</html>
