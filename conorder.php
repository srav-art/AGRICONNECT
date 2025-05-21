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

// Fetch orders that have not been confirmed yet
$sql = "SELECT o.order_id, o.merchant_name, o.phone, o.address, o.quantity, o.order_date, 
               c.crop_name, c.image 
        FROM orders o 
        JOIN crops c ON o.crop_id = c.id 
        WHERE NOT EXISTS (SELECT 1 FROM order_confirmations oc WHERE oc.order_id = o.order_id)";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Orders</title>
    <link rel="stylesheet" href="conorder.css">
</head>
<body>

<div class="container">
    <h1>Confirm Orders</h1>

    <div class="crop-card-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="crop-card">
                <img src="<?php echo $row['image']; ?>" alt="Crop Image">
                <p><strong>Crop Name:</strong> <?php echo $row['crop_name']; ?></p>
                <p><strong>Merchant Name:</strong> <?php echo $row['merchant_name']; ?></p>
                <p><strong>Quantity:</strong> <?php echo $row['quantity']; ?></p>
                <p><strong>Order Date:</strong> <?php echo $row['order_date']; ?></p>
                <form method="POST" action="confirmorder.php">
                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                    <button type="submit" name="action" value="accept">Accept</button>
                    <button type="submit" name="action" value="reject">Reject</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <button class="back-button" onclick="location.href='farmer.html'">Back</button>
</div>

</body>
</html>