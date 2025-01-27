<?php
session_start();
include('../db_connection.php');

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] == 0) {
    echo "Access denied. Admins only.";
    exit();
}

// Handle user blocking/unblocking, promotion/demotion, or deletion if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    if (isset($_POST['block'])) {
        $query = "UPDATE users SET status = 'blocked' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    if (isset($_POST['unblock'])) {
        $query = "UPDATE users SET status = 'active' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    if (isset($_POST['delete'])) {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    if (isset($_POST['promote'])) {
        $query = "UPDATE users SET is_admin = 1 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    if (isset($_POST['demote'])) {
        $query = "UPDATE users SET is_admin = 0 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    header("Location: manage_user.php");
    exit();
}

// Fetch all users for display
$sql = "SELECT id, email, status, is_admin FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../../css/home.css">
    <style>
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        /* Button styles */
        .action {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }

        .block {
            background-color: #f44336;
            color: white;
        }

        .unblock {
            background-color: #4caf50;
            color: white;
        }

        .delete {
            background-color: #000;
            color: white;
        }

        .promote {
            background-color: #3f51b5;
            color: white;
        }

        .demote {
            background-color: #ff9800;
            color: white;
        }
        /* Centered Heading Style */
        h1 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }

        /* Caution message styles */
        .caution {
            background-color: #f39c12;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 10px 20px;
            margin-bottom: 20px;
            margin-left: 4rem;
            margin-right: 4rem;
            display: inline-block;
            text-align: center;
        }

        .caution:hover {
            background-color: #e67e22;
        }

        .block:hover {
            background-color: #d32f2f;
        }

        .unblock:hover {
            background-color: #388e3c;
        }

        .delete:hover {
            background-color: #444;
        }

        .promote:hover {
            background-color: #303f9f;
        }

        .demote:hover {
            background-color: #f57c00;
        }

        /* Mobile View Styles */
        @media (max-width: 600px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                text-align: left;
                padding: 8px;
            }

            .action {
                font-size: 12px;
                padding: 5px;
            }

            header {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
            }

            .logo img {
                max-width: 80px;
            }

            nav ul {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                margin: 10px 0 0 0;
                padding: 0;
                list-style: none;
            }

            nav ul li a {
                text-decoration: none;
                color: #000;
                font-size: 14px;
            }

            footer {
                text-align: center;
                padding: 15px;
                font-size: 14px;
            }

            tbody tr {
                display: block;
                margin-bottom: 10px;
            }

            tbody tr td {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            thead {
                display: none;
            }

            td {
                display: block;
                text-align: left;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                display: inline-block;
                width: 120px;
            }

            td form {
                margin-bottom: 5px;
            }
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
            <ul>
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
        <h1>Manage Users</h1>

        <!-- Caution message -->
        <div class="caution">
            <strong>Important Notice:</strong> Please exercise caution when managing user statuses and roles. Actions such as blocking, unblocking, promoting, or deleting users affect system operations and user access. Ensure that each action is thoroughly considered before proceeding.
        </div>


        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="User ID"><?php echo htmlspecialchars($row['id']); ?></td>
                        <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td data-label="Status"><?php echo htmlspecialchars($row['status']); ?></td>
                        <td data-label="Role"><?php echo $row['is_admin'] ? 'Admin' : 'User'; ?></td>
                        <td data-label="Actions">
                            <?php if ($row['status'] !== 'blocked'): ?>
                                <!-- Show the Block button if the user is not blocked -->
                                <form action="manage_user.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="block" class="action block">Block</button>
                                </form>
                            <?php else: ?>
                                <!-- Show the Unblock button if the user is blocked -->
                                <form action="manage_user.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="unblock" class="action unblock">Unblock</button>
                                </form>
                            <?php endif; ?>
                            <?php if (!$row['is_admin']): ?>
                                <form action="manage_user.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="promote" class="action promote">Promote</button>
                                </form>
                            <?php else: ?>
                                <form action="manage_user.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="demote" class="action demote">Demote</button>
                                </form>
                            <?php endif; ?>
                            <form action="manage_user.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="action delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    <br><br><br>
    <br><br><br>
    <br><br><br>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> TTU Student Hostels. All rights reserved.</p>
    </footer>
</body>
</html>
