<?php
//local
/*
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ttu_student_hostels";


//online
$servername = "srv470.hstgr.io";
$username = "u629946986_group3";
$password = "";
$dbname = "u629946986_ttu_std_hostel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
*/


//hide db cred


// Load the .env file
loadEnv(__DIR__ . '/.env'); // Correct path to the .env file

// Use environment variables for database credentials
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

// Create the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

/* Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
*/
// Function to load .env variables
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }

        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

?>
