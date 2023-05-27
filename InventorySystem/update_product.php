<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Update Product</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
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

            if (isset($_GET['id'])) {
                $productId = $_GET['id'];

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $name = $_POST['name'];
                    $quantity = $_POST['quantity'];
                    $price = $_POST['price'];
                    $image = $_FILES['image']['name'];
                    $tmpImage = $_FILES['image']['tmp_name'];

                    if (!empty($image)) {
                        // Move the uploaded image to the target directory
                        $targetDirectory = 'images/';
                        $targetPath = $targetDirectory . $image;
                        move_uploaded_file($tmpImage, $targetPath);
                    }

                    $updateQuery = "UPDATE products SET name = :name, quantity = :quantity, price = :price, image = :image WHERE id = :id";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':quantity', $quantity);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':image', $image);
                    $stmt->bindParam(':id', $productId);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Product updated successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Error updating product.</div>";
                    }
                }

                $selectQuery = "SELECT * FROM products WHERE id = :id";
                $stmt = $conn->prepare($selectQuery);
                $stmt->bindParam(':id', $productId);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    ?>

                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Product Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>

                    <?php
                } else {
                    echo "<div class='alert alert-danger'>Product not found.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Invalid product ID.</div>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>

    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
