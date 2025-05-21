<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP (leave blank)
$dbname = "farm_database"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if negotiation ID and action are provided
    if (isset($_POST['negotiation_id']) && isset($_POST['action'])) {
        $negotiation_id = $_POST['negotiation_id'];
        $action = $_POST['action'];

        // Determine the new status based on the action (accept or reject)
        if ($action == 'accept') {
            $new_status = 'Accepted';
        } elseif ($action == 'reject') {
            $new_status = 'Rejected';
        } else {
            $new_status = 'Pending'; // Default fallback
        }

        // Update the status of the negotiation in the database
        $sql = "UPDATE negotiations SET status = ? WHERE negotiation_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $negotiation_id);

        if ($stmt->execute()) {
            // Redirect back to the negotiation requests page with success message
            echo "<script>
                    alert('Negotiation status updated successfully.');
                    window.location.href = 'viewneg.php'; // Redirect back to negotiation list page
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