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

$merchant_name = "";
$order_details = null;
$success_message = "";

// Fetch order details based on the merchant_name
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['merchant_name'])) {
    $merchant_name = $_POST['merchant_name'];

    // SQL query to retrieve order details
    $sql = "SELECT o.order_id, o.merchant_name, o.phone, o.address, o.quantity, o.order_date, 
                   c.crop_name, c.price, c.image 
            FROM orders o 
            JOIN crops c ON o.crop_id = c.id 
            WHERE o.merchant_name = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $merchant_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order_details = $result->fetch_assoc();
    } else {
        echo "No orders found for this merchant.";
    }
}

// Handle negotiation form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['proposed_price'])) {
    $proposed_price = $_POST['proposed_price'];
    $order_id = $_POST['order_id'];

    // Insert negotiation request into the negotiations table
    $insert_sql = "INSERT INTO negotiations (order_id, merchant_name, proposed_price, crop_name) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("isds", $order_id, $merchant_name, $proposed_price, $order_details['crop_name']);

    if ($stmt->execute()) {
        $success_message = "Negotiation request sent successfully!";
        
        // Fetch the order details again to display them after submission
        $sql = "SELECT o.order_id, o.merchant_name, o.phone, o.address, o.quantity, o.order_date, 
                       c.crop_name, c.price, c.image 
                FROM orders o 
                JOIN crops c ON o.crop_id = c.id 
                WHERE o.order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $order_details = $result->fetch_assoc();
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Negotiate Price</title>
    <link rel="stylesheet" href="negotiate.css">
</head>
<body>

<div class="container">
    <h1>Negotiate Price</h1>

    <?php if (!empty($success_message)): ?>
        <p class="success-message"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if ($order_details): ?>
        <div class="crop-card">
            <img src="<?php echo $order_details['image']; ?>" alt="Crop Image">
            <p><strong>Crop Name:</strong> <?php echo $order_details['crop_name']; ?></p>
            <p><strong>Initial Price:</strong> $<?php echo $order_details['price']; ?></p>
            <p><strong>Merchant Name:</strong> <?php echo $order_details['merchant_name']; ?></p>
            <p><strong>Phone:</strong> <?php echo $order_details['phone']; ?></p>
            <p><strong>Address:</strong> <?php echo $order_details['address']; ?></p>
        </div>

        <form action="" method="post">
            <div class="form-group">
                <label for="proposed_price">Proposed Price:</label>
                <input type="number" id="proposed_price" name="proposed_price" required>
            </div>
            <input type="hidden" name="order_id" value="<?php echo $order_details['order_id']; ?>">
            <div class="form-group">
                <button type="submit">Submit Negotiation</button>
            </div>
        </form>
    <?php else: ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="merchant_name">Enter Merchant Name:</label>
                <input type="text" id="merchant_name" name="merchant_name" required>
            </div>
            <div class="form-group">
                <button type="submit">Find Order</button>
            </div>
        </form>
    <?php endif; ?>

    <button class="back-button" onclick="location.href='merchant.html'">Back</button>
</div>

</body>
</html>