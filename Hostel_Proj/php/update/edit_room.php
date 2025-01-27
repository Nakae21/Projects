<?php
session_start();
include('../db_connection.php');

// Check admin access
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] == 0) {
    echo "Access denied. Admins only.";
    exit();
}

// Check if username exists in session
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Guest";

// Handle room update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $room_name = $_POST['room_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];

    $sql = "UPDATE rooms SET room_name = ?, description = ?, price = ?, capacity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdii", $room_name, $description, $price, $capacity, $room_id);

    if ($stmt->execute()) {
        echo "<script>alert('Room updated successfully.');</script>";
    } else {
        echo "<script>alert('Failed to update room.');</script>";
    }

    $stmt->close();
}

// Fetch all rooms
$result = $conn->query("SELECT * FROM rooms");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room - TTU Student Hostels</title>
    <link rel="stylesheet" href="../../css/home.css">
    <style>
        /* Internal CSS for Main Content Only */
        .edit-room {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .edit-room h1 {
            text-align: center;
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
        }

        .edit-room form {
            display: flex;
            flex-direction: column;
        }

        .edit-room label {
            font-size: 1rem;
            margin-bottom: 5px;
            color: #555;
        }

        .edit-room input,
        .edit-room textarea,
        .edit-room select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .edit-room button {
            background-color: #0078d4;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-room button:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <a href="../../index.php">
                <img src="../../images/logo.png" alt="TTU Logo">
            </a>
        </div>
        <nav>
            <ul class="menu">
                <li><a href="../../index.php">Home</a></li>
                <?php if ($_SESSION['is_admin']): ?>
                    <li><a href="../../pages/admin_dashboard.php">Manage</a></li>
                <?php endif; ?>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <section class="edit-room">
            <h1>Edit Room</h1>
            <form method="post">
                <label for="room_id">Select Room:</label>
                <select name="room_id" required>
                    <option value="">--Select Room--</option>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['room_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="room_name">Room Name:</label>
                <input type="text" name="room_name" required>

                <label for="description">Description:</label>
                <textarea name="description" required></textarea>

                <label for="price">Price:</label>
                <input type="number" name="price" required>

                <label for="capacity">Capacity:</label>
                <input type="number" name="capacity" required>

                <button type="submit">Update Room</button>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> TTU Student Hostels. All Rights Reserved.</p>
    </footer>
</body>
</html>
