<?php
session_start();
include('../db_connection.php');

// Check admin access
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] == 0) {
    echo "Access denied. Admins only.";
    exit();
}

// Fetch the username (if it exists)
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Handle room deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['room'])) {
    $room_id = $_POST['room'];

    // Ask for confirmation before executing the query
    echo "<script>
        var confirmDeletion = confirm('Are you sure you want to delete this room? This action cannot be undone.');
        if (confirmDeletion) {
            // Proceed with deletion
            window.location.href = 'delete_room.php?delete=true&room=' + $room_id;
        } else {
            window.location.href = 'delete_room.php'; // Refresh page if deletion is canceled
        }
    </script>";
}

// Proceed with deletion if confirmed
if (isset($_GET['delete']) && $_GET['delete'] == 'true' && isset($_GET['room'])) {
    $room_id = $_GET['room'];

    $sql = "DELETE FROM rooms WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $room_id);

    if ($stmt->execute()) {
        echo "<script>alert('Room deleted successfully.');</script>";
    } else {
        echo "<script>alert('Failed to delete room.');</script>";
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
    <title>Delete Room - TTU Student Hostels</title>
    <link rel="stylesheet" href="../../css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Internal CSS */
        .del-sec {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .del-sec h1 {
            text-align: center;
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
        }

        .del-sec form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .del-sec label {
            font-size: 1rem;
            margin-bottom: 10px;
            color: #555;
        }

        .del-sec select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .del-sec .btn {
            background-color: #0078d4;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .del-sec .btn:hover {
            background-color: #005bb5;
        }

        .caution-note {
            background-color: #ffcc00;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 1.1rem;
            color: #d9534f;
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
                <li><a href="../../php/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Delete Room Section -->
    <main>
        <section class="del-sec">
            <h1>Delete Room</h1>

            <!-- Caution Note -->
            <div class="caution-note">
                <strong>Warning:</strong> Deleting a room is permanent and cannot be undone. Make sure you have selected the correct room.
            </div>

            <form method="post">
                <label for="room">Select Room to Delete:</label>
                <select name="room" id="room" required>
                    <option value="">--Select Room--</option>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['room_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" class="btn">Delete Room</button>
            </form>
        </section>
    </main>
    <br><br>
    <br><br>
    <br><br>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> TTU Student Hostels. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript -->
    <script src="../js/script.js"></script>
</body>
</html>
