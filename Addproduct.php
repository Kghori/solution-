<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'your_database'); // Update with your DB details

// Fetch categories for the category dropdown
$categoryResult = $conn->query("SELECT * FROM category");

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $categoryId = $_POST['category'];
    $subcategoryId = $_POST['subcategory'];

    // Insert the product data into the product table
    $insertQuery = "INSERT INTO product (product_name, price, category_id, subcategory_id) 
                    VALUES ('$productName', '$price', '$categoryId', '$subcategoryId')";

    if ($conn->query($insertQuery) === TRUE) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// If a category is selected, fetch its corresponding subcategories
$selectedCategory = isset($_POST['category']) ? $_POST['category'] : '';
$subcategoryResult = null;

if ($selectedCategory) {
    $subcategoryResult = $conn->query("SELECT * FROM subcategory WHERE category_id = $selectedCategory");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>

<h2>Add New Product</h2>

<form action="" method="POST">
    <label for="product_name">Product Name:</label>
    <input type="text" name="product_name" required>
    <br><br>

    <label for="price">Price:</label>
    <input type="text" name="price" required>
    <br><br>

    <label for="category">Category:</label>
    <select id="category" name="category" onchange="this.form.submit()">
        <option value="">Select Category</option>
        <?php
        while ($row = $categoryResult->fetch_assoc()) {
            $selected = ($row['id'] == $selectedCategory) ? 'selected' : '';
            echo "<option value='" . $row['id'] . "' $selected>" . $row['category_name'] . "</option>";
        }
        ?>
    </select>
    <br><br>

    <label for="subcategory">Subcategory:</label>
    <select id="subcategory" name="subcategory">
        <option value="">Select Subcategory</option>
        <?php
        if ($subcategoryResult) {
            while ($row = $subcategoryResult->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['subcategory_name'] . "</option>";
            }
        }
        ?>
    </select>
    <br><br>

    <button type="submit">Add Product</button>
</form>

</body>
</html>
