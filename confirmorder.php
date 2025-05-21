<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farm_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['order_id']) && isset($_POST['action'])) {
        $order_id = $_POST['order_id'];
        $action = $_POST['action'];
        $farmer_name = "Farmer Name"; // Replace this with the actual farmer's name

        // Determine the new status based on the action (accept or reject)
        if ($action == 'accept') {
            $confirmation_status = 'Confirmed';
        } elseif ($action == 'reject') {
            $confirmation_status = 'Rejected';
        }

        // Insert the confirmation status into the order_confirmations table
        $sql = "INSERT INTO order_confirmations (order_id, confirmed_by, confirmation_status) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $order_id, $farmer_name, $confirmation_status);

        if ($stmt->execute()) {
            // Redirect back to the confirm orders page with success message
            echo "<script>
                    alert('Order status updated successfully.');
                    window.location.href = 'conorder.php'; // Redirect back to confirm orders page
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>