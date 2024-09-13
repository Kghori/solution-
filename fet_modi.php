<!DOCTYPE html>
<html>
<head>
    <title>Modify Subcategory</title>
</head>
<body>
    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "your_database_name");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch subcategory ID from URL
    $subCateId = intval($_GET['id']);

    // Fetch subcategory details
    $sql = "SELECT sc.id AS sub_cate_id, sc.name AS sub_cate_name, c.id AS cate_id, c.name AS cate_name
            FROM sub_cate sc
            JOIN cate c ON sc.cate_id = c.id
            WHERE sc.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subCateId);
    $stmt->execute();
    $result = $stmt->get_result();
    $subCate = $result->fetch_assoc();

    if (!$subCate) {
        die("Subcategory not found.");
    }

    // Fetch all categories for dropdown
    $categorySql = "SELECT id, name FROM cate";
    $categories = $conn->query($categorySql);
    ?>

    <h2>Modify Subcategory</h2>
    <form method="post" action="">
        <input type="hidden" name="sub_cate_id" value="<?php echo $subCate['sub_cate_id']; ?>">
        <label>Subcategory Name:</label>
        <input type="text" name="new_name" value="<?php echo htmlspecialchars($subCate['sub_cate_name']); ?>" required><br>
        <label>Category:</label>
        <select name="cate_id">
            <?php
            while ($category = $categories->fetch_assoc()) {
                echo "<option value='" . $category['id'] . "'" . ($category['id'] == $subCate['cate_id'] ? " selected" : "") . ">" . htmlspecialchars($category['name']) . "</option>";
            }
            ?>
        </select><br>
        <input type="submit" name="update" value="Update">
    </form>

    <?php
    // Handle form submission
    if (isset($_POST['update'])) {
        $subCateId = $_POST['sub_cate_id'];
        $newName = $_POST['new_name'];
        $cateId = $_POST['cate_id'];

        // Update subcategory
        $updateSql = "UPDATE sub_cate SET name = ?, cate_id = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("sii", $newName, $cateId, $subCateId);

        if ($stmt->execute()) {
            echo "Subcategory updated successfully.<br>";
        } else {
            echo "Error updating subcategory: " . $stmt->error . "<br>";
        }

        $stmt->close();
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>
