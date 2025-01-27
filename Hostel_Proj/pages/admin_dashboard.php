<?php
session_start();
include('../php/db_connection.php');

// Ensure the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Check if the user is an admin
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($is_admin);
$query->fetch();
$query->close();

if ($is_admin == 0) {
    echo "Access denied. Admins only.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TTU Student Hostels</title>
    <link rel="stylesheet" href="../css/home.css">
    <style>
        main {
            padding: 20px;
        }

        .admin-dashboard {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .admin-dashboard h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            margin: 10px 5px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1em;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .button-container {
                flex-direction: column;
                align-items: stretch;
            }

            .button {
                width: 100%;
                text-align: center;
            }
        }

        .admin-guidelines {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .admin-guidelines h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .admin-guidelines ul {
            list-style-type: disc;
            padding-left: 20px;
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
    </header>

    <!-- Main Content -->
    <main>
        <section class="admin-dashboard">
            <h1>Welcome, Admin!</h1>
            <p>Use the buttons below to manage rooms and users. Please proceed with caution, as these actions can have significant impacts on the system.</p>

            <!-- Admin Guidelines -->
            <section class="admin-guidelines">
                <h2>Admin Guidelines & Warnings</h2>
                <ul>
                    <li><strong>Adding Rooms:</strong> Ensure all room details (e.g., type, price, availability) are accurate before adding a new room.</li>
                    <li><strong>Editing Rooms:</strong> Carefully review the current details of a room before making changes to avoid accidental loss of information.</li>
                    <li><strong>Deleting Rooms:</strong> Deleting a room will permanently remove it from the system. This action cannot be undone. Double-check the room details before deleting.</li>
                    <li><strong>Managing Users:</strong> You have the authority to block or unblock users. Make sure the actions you take align with the platform's rules and user agreements.</li>
                </ul>
                <p>Always take great care when performing these administrative tasks. Mistakes can impact the system's functionality or affect users' experience.</p>
            </section>

            <!-- Action Buttons -->
            <div class="button-container">
                <a href="../pages/add_room.php" class="button">Add Room</a>
                <a href="../php/update/edit_room.php" class="button">Edit Room</a>
                <a href="../php/update/delete_room.php" class="button">Delete Room</a>
                <a href="../php/update/manage_user.php" class="button">Manage Users</a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 TTU Student Hostels. All rights reserved.</p>
    </footer>
</body>
</html>

