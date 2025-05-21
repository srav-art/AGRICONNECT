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

// Fetch negotiation requests
$sql = "SELECT n.negotiation_id, o.merchant_name, n.proposed_price, c.crop_name, o.order_date, c.image 
        FROM negotiations n 
        JOIN orders o ON n.order_id = o.order_id
        JOIN crops c ON o.crop_id = c.id 
        WHERE n.status = 'Pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer - View Negotiations</title>
    <link rel="stylesheet" href="viewneg.css">
    
</head>
<body>

<div class="container">
    <h1>Negotiation Requests</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="crop-card">
                <img src="<?php echo $row['image']; ?>" alt="Crop Image">
                <p><strong>Crop Name:</strong> <?php echo $row['crop_name']; ?></p>
                <p><strong>Merchant Name:</strong> <?php echo $row['merchant_name']; ?></p>
                <p><strong>Proposed Price:</strong> $<?php echo $row['proposed_price']; ?></p>
                <form method="POST" action="updatenegstatus.php">
                    <input type="hidden" name="negotiation_id" value="<?php echo $row['negotiation_id']; ?>">
                    <button type="submit" name="action" value="accept">Accept</button>
                    <button type="submit" name="action" value="reject">Reject</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No pending negotiation requests found.</p>
    <?php endif; ?>

    <button class="back-button" onclick="location.href='farmer.html'">Back</button>
</div>

</body>
</html>