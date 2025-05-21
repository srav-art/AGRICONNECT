<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farm_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders from the orders table, including the address
$sql = "SELECT o.order_id, o.crop_id, o.merchant_name, o.quantity, o.address,o.phone,o.order_date, c.crop_name, c.price 
        FROM orders o
        JOIN crops c ON o.crop_id = c.id";  // Ensure 'address' is selected
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="vieworders.css">
</head>
<body>
    <div class="container">
        <h1>Order History</h1>

        <?php
        if ($result->num_rows > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Crop Name</th>
                            <th>Merchant Name</th>
                            <th>Quantity</th>
                            <th>Address</th> <!-- New Address column -->
                            <th>Phone</th>
                            <th>Price</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>";
            
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['order_id'] . "</td>
                        <td>" . $row['crop_name'] . "</td>
                        <td>" . $row['merchant_name'] . "</td>
                        <td>" . $row['quantity'] . "</td>
                        <td>" . $row['address'] . "</td> <!-- Display the address -->
                        <td>" . $row['phone'] . "</td> 
                        <td>" . $row['price'] . "</td>
                        <td>" . $row['order_date'] . "</td>
                      </tr>";
            }
            
            echo "</tbody></table>";
        } else {
            echo "<p>No orders found.</p>";
        }

        // Close the connection
        $conn->close();
        ?>

        <a href="farmer.html" class="back-button">Back</a>
    </div>
</body>
</html>