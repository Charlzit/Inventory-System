<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Add Product</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
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
                if (isset($_POST['name']) && isset($_POST['quantity']) && isset($_POST['price'])) {
                    $name = $_POST['name'];
                    $quantity = $_POST['quantity'];
                    $price = $_POST['price'];

                    if ($_FILES['image']['name']) {
                        $imageFileName = $_FILES['image']['name'];
                        $imageTmpName = $_FILES['image']['tmp_name'];
                        $imageUploadPath = 'images/' . $imageFileName;

                        move_uploaded_file($imageTmpName, $imageUploadPath);
                    } else {
                        $imageFileName = '';
                    }

                    $insertQuery = "INSERT INTO products (name, quantity, price, image) VALUES (:name, :quantity, :price, :image)";
                    $stmt = $conn->prepare($insertQuery);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':quantity', $quantity);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':image', $imageFileName);
                    $stmt->execute();

                    echo "<div class='alert alert-success' role='alert'>Product added successfully!</div>";
                }
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
        }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" name="quantity" id="quantity" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" name="price" id="price" required>
            </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control" name="image" id="image">
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
