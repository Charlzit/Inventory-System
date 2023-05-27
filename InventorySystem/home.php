<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Inventory System</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                    <a class="nav-link" href="add_product.php">Add Product</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="POS.php">POS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Products</a>
                </li>
            </ul>
        </nav>

        <?php
        $db_username = 'root';
        $db_password = '';
        $conn = new PDO('mysql:host=localhost;dbname=inventory', $db_username, $db_password);

        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['update_id'])) {
                    // Update product logic here
                }

                if (isset($_POST['name']) && isset($_POST['quantity']) && isset($_POST['price'])) {
                    // Add new product logic here
                }

                if (isset($_POST['delete_id'])) {
                    // Delete product logic here
                }

                if (isset($_POST['product_id'])) {
                    $selectedProductId = $_POST['product_id'];
                    $selectProductQuery = "SELECT * FROM products WHERE id = :product_id";
                    $stmt = $conn->prepare($selectProductQuery);
                    $stmt->bindParam(':product_id', $selectedProductId);
                    $stmt->execute();
                    $selectedProduct = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($selectedProduct) {
                        echo "<h3>Selected Product:</h3>";
                        echo "<p>Product ID: " . $selectedProduct['id'] . "</p>";
                        echo "<p>Product Name: " . $selectedProduct['name'] . "</p>";
                        echo "<p>Quantity: " . $selectedProduct['quantity'] . "</p>";
                        echo "<p>Price: " . $selectedProduct['price'] . "</p>";
                        echo "<p>Image:</p>";
                        if ($selectedProduct['image']) {
                            echo "<img src='images/" . $selectedProduct['image'] . "' style='width: 100px; height: 100px;' />";
                        } else {
                            echo "No image available";
                        }
                    } else {
                        echo "Selected product not found.";
                    }
                }
            }

            $query = "SELECT * FROM products";
            $result = $conn->query($query);

            echo "<h2>Products</h2>";

            if ($result->rowCount() > 0) {
                echo "<form action='' method='POST'>";
                echo "<div class='form-group'>";
                echo "<label for='product_id'>Select Product:</label>";
                echo "<select name='product_id' class='form-control'>";
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
                echo "</select>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>Submit</button>";
                echo "</form>";
                echo "<br>";
            }

            // Rest of your existing code for displaying products table and forms
            // ...

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
