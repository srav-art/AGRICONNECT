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

$successMessage = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cropId = $_POST['crop_id'];
    $merchantName = $_POST['merchant_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $quantity = $_POST['quantity'];

    // Insert order into the orders table
    $stmt = $conn->prepare("INSERT INTO orders (crop_id, merchant_name, quantity, phone, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiss", $cropId, $merchantName, $quantity, $phone, $address);

    if ($stmt->execute()) {
        $successMessage = "Order placed successfully!";
    } else {
        $successMessage = "Error placing order: " . $stmt->error;
    }

    $stmt->close();
}

$cropId = isset($_GET['crop_id']) ? $_GET['crop_id'] : '';
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : '';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="placeorderdetails.css">
</head>
<body>
    <div class="container">
        <h1>Enter Order Details</h1>

        <!-- Display success message if the order is placed -->
        <?php if (!empty($successMessage)): ?>
            <p class="success-message"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="crop_id" value="<?php echo $cropId; ?>">
            <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">

            <label for="merchant_name">Merchant Name:</label>
            <input type="text" name="merchant_name" required>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" required>

            <label for="address">Address:</label>
            <textarea name="address" required></textarea>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="<?php echo $quantity; ?>" readonly>

            <button type="submit">Confirm Order</button>
        </form>
        <a href="negotiate.php" class="back-button">Negotiate price</a>

        <a href="placeorders.php" class="back-button">Back</a>
    </div>
</body>
</html>