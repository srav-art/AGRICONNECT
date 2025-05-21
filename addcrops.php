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

// Initialize a variable to hold the success message
$successMessage = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cropName = $_POST['cropName'];
    $cropType = $_POST['cropType'];
    $quantity = $_POST['quantity'];
    $quantityUnit = $_POST['quantityUnit'];
    $price = $_POST['price'];
    $priceUnit = $_POST['priceUnit'];
    $harvestDate = $_POST['harvestDate'];
    $description = $_POST['description'];

    // Image handling
    $targetDir = "uploads/"; // Folder where images will be stored
    $imageFileName = basename($_FILES["cropImage"]["name"]);
    $targetFilePath = $targetDir . $imageFileName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Validate image
    $check = getimagesize($_FILES["cropImage"]["tmp_name"]);
    if ($check !== false) {
        // Check file size (limit to 2MB)
        if ($_FILES["cropImage"]["size"] > 2000000) {
            $successMessage = "Sorry, your file is too large.";
        }
        // Allow certain file formats
        elseif (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            $successMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            // Move file to server and insert crop details into the database
            if (move_uploaded_file($_FILES["cropImage"]["tmp_name"], $targetFilePath)) {
                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO crops (crop_name, crop_type, quantity, quantity_unit, price, price_unit, harvest_date, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdsdssss", $cropName, $cropType, $quantity, $quantityUnit, $price, $priceUnit, $harvestDate, $description, $targetFilePath);

                // Execute the statement
                if ($stmt->execute()) {
                    // Redirect with success message
                    header("Location: addcrops1.php?message=Crop added successfully!");
                    exit;
                } else {
                    $successMessage = "Error: " . $stmt->error; // Display the error message if execution fails
                }

                // Close statement
                $stmt->close();
            } else {
                $successMessage = "Sorry, there was an error uploading your image.";
            }
        }
    } else {
        $successMessage = "File is not an image.";
    }
    
    $conn->close();
}
?>