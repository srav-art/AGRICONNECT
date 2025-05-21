<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP (leave blank)
$dbname = "farm_database"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch crops
$sql = "SELECT id,crop_name, crop_type, quantity, quantity_unit, price, price_unit, harvest_date, description, image FROM crops";
$result = $conn->query($sql);

$crops = array();

if ($result->num_rows > 0) {
    // Fetch all crops
    while($row = $result->fetch_assoc()) {
        $crops[] = $row;
    }
}

$conn->close();

// Return crops as JSON
header('Content-Type: application/json');
echo json_encode($crops);
?>