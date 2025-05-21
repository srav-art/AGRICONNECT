<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Crops</title>
    <link rel="stylesheet" href="addcrops.css">
</head>
<body>
    <div class="container">
        <h2>Add New Crop</h2>

        <!-- Display success message if available -->
        <?php if (isset($_GET['message'])): ?>
            <p class="success-message"><?php echo htmlspecialchars($_GET['message']); ?></p>
        <?php endif; ?>

        <form id="addCropForm" method="POST" action="addcrops.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="cropName">Crop Name:</label>
                <input type="text" id="cropName" name="cropName" required>
            </div>

            <div class="form-group">
                <label for="cropType">Crop Type:</label>
                <select id="cropType" name="cropType" required>
                    <option value="fruit">Fruit</option>
                    <option value="vegetable">Vegetable</option>
                    <option value="grain">Grain</option>
                    <option value="herb">Herb</option>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required>
                <select id="quantityUnit" name="quantityUnit" required>
                    <option value="kg">kg</option>
                    <option value="ton">ton</option>
                    <option value="quintals">quintals</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price per Unit:</label>
                <input type="number" id="price" name="price" required>
                <select id="priceUnit" name="priceUnit" required>
                    <option value="kg">per kg</option>
                    <option value="ton">per ton</option>
                    <option value="quintals">per quintal</option>
                </select>
            </div>

            <div class="form-group">
                <label for="harvestDate">Expected Harvest Date:</label>
                <input type="date" id="harvestDate" name="harvestDate" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" placeholder="Optional"></textarea>
            </div>

            <div class="form-group">
                <label for="cropImage">Upload Crop Image:</label>
                <input type="file" id="cropImage" name="cropImage" accept="image/*" required>
            </div>

            <button type="submit">Add Crop</button>
        </form>
        <button class="back-button" onclick="location.href='farmer.html'">Back</button>
    </div>
</body>
</html>