///fetch and display sub cate 
<!DOCTYPE html>
<html>
<head>
    <title>Subcategories</title>
</head>
<body>
    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "your_database_name");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch subcategories along with their categories
    $sql = "SELECT sc.id AS sub_cate_id, sc.name AS sub_cate_name, c.name AS cate_name
            FROM sub_cate sc
            JOIN cate c ON sc.cate_id = c.id";
    $result = $conn->query($sql);
    ?>

    <h2>Subcategories</h2>
    <table border="1">
        <tr>
            <th>Subcategory ID</th>
            <th>Subcategory Name</th>
            <th>Category Name</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['sub_cate_id'] . "</td>
                        <td>" . $row['sub_cate_name'] . "</td>
                        <td>" . $row['cate_name'] . "</td>
                        <td><a href='modify.php?id=" . $row['sub_cate_id'] . "'>Modify</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No subcategories available</td></tr>";
        }

        // Close connection
        $conn->close();
        ?>
    </table>
</body>
</html>
