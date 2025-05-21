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

// Fetch crops for display
$sql = "SELECT id, crop_name, crop_type, quantity, quantity_unit, price, price_unit, image FROM crops";
$result = $conn->query($sql);

$crops = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $crops[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Orders</title>
    <link rel="stylesheet" href="placeorders.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Place Orders</h1>
            <a href="merchant.html" class="back-button">Back</a>
        </header>

        <section class="crops-list">
            <?php if (count($crops) > 0): ?>
                <?php foreach ($crops as $crop): ?>
                    <div class="crop-card">
                        <img src="<?php echo $crop['image']; ?>" alt="<?php echo $crop['crop_name']; ?>">
                        <h2><?php echo $crop['crop_name']; ?></h2>
                        <p>Type: <?php echo $crop['crop_type']; ?></p>
                        <p>Price: $<?php echo $crop['price']; ?> per <?php echo $crop['price_unit']; ?></p>
                        <p>Available: <?php echo $crop['quantity'] . ' ' . $crop['quantity_unit']; ?></p>

                        <!-- Redirect to place_order_details.php with crop ID and quantity -->
                        <form action="placeorderdetails.php" method="GET" class="order-form">
                            <input type="hidden" name="crop_id" value="<?php echo $crop['id']; ?>">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" min="1" max="<?php echo $crop['quantity']; ?>" required>
                            <button type="submit">Place Order</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No crops available to order.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>