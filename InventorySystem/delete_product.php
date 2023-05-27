<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Delete Product</h1>

        <?php
        $db_username = 'root';
        $db_password = '';
        $conn = new PDO('mysql:host=localhost;dbname=inventory', $db_username, $db_password);

        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_GET['id'])) {
                $productId = $_GET['id'];

                $selectQuery = "SELECT * FROM products WHERE id = :id";
                $stmt = $conn->prepare($selectQuery);
                $stmt->bindParam(':id', $productId);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    ?>

                    <h3>Are you sure you want to delete this product?</h3>
                    <p><strong>Product ID:</strong> <?php echo $product['id']; ?></p>
                    <p><strong>Product Name:</strong> <?php echo $product['name']; ?></p>
                    <p><strong>Quantity:</strong> <?php echo $product['quantity']; ?></p>
                    <p><strong>Price:</strong> <?php echo $product['price']; ?></p>

                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $productId; ?>">
                        <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                        <a href="products.php" class="btn btn-secondary">Cancel</a>
                    </form>

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
                        $deleteQuery = "DELETE FROM products WHERE id = :id";
                        $stmt = $conn->prepare($deleteQuery);
                        $stmt->bindParam(':id', $productId);

                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Product deleted successfully.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error deleting product.</div>";
                        }
                    }
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
