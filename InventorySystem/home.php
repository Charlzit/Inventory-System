<?php
$db_username = 'root';
$db_password = '';
$conn = new PDO('mysql:host=localhost;dbname=inventory', $db_username, $db_password);

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $createTableQuery = "CREATE TABLE IF NOT EXISTS products (
                            id INT PRIMARY KEY AUTO_INCREMENT,
                            name VARCHAR(255) NOT NULL,
                            quantity INT NOT NULL,
                            price DECIMAL(10,2) NOT NULL,
                            image VARCHAR(255)
                        )";
    $conn->exec($createTableQuery);
    echo "Products table created successfully!<br><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_id'])) {
            $updateId = $_POST['update_id'];
            $newName = $_POST['new_name'];
            $newQuantity = $_POST['new_quantity'];
            $newPrice = $_POST['new_price'];

            $updateQuery = "UPDATE products SET name = :newName, quantity = :newQuantity, price = :newPrice WHERE id = :id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':newName', $newName);
            $stmt->bindParam(':newQuantity', $newQuantity);
            $stmt->bindParam(':newPrice', $newPrice);
            $stmt->bindParam(':id', $updateId);
            $stmt->execute();

            echo "Product updated successfully!<br><br>";
        }


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

            echo "New product added successfully!<br><br>";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];

        $deleteQuery = "DELETE FROM products WHERE id = :id";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bindParam(':id', $deleteId);
        $stmt->execute();

        echo "Product deleted successfully!<br><br>";
    }
}

$query = "SELECT * FROM products";
$result = $conn->query($query);

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    echo "Product ID: " . $row['id'] . "<br>";
    echo "Product Name: " . $row['name']. "<br>";
    echo "Quantity: " . $row['quantity'] . "<br>";
    echo "Price: $" . $row['price'] . "<br>";
    if ($row['image']) {
        echo "<img src='images/" . $row['image'] . "' style='width: 200px; height: auto;' /><br>";
    }
    echo "<form action='' method='POST' enctype='multipart/form-data' style='display: inline;'>";
    echo "<input type='hidden' name='update_id' value='" . $row['id'] . "'>";
    echo "<div class='form-group row'>";
    echo "<label for='new_name' class='col-sm-2 col-form-label'>New Name:</label>";
    echo "<div class='col-sm-4'>";
    echo "<input type='text' class='form-control' name='new_name' id='new_name_" . $row['id'] . "' required>";
    echo "</div>";
    echo "</div>";

    echo "<div class='form-group row'>";
    echo "<label for='new_quantity' class='col-sm-2 col-form-label'>New Quantity:</label>";
    echo "<div class='col-sm-4'>";
    echo "<input type='number' class='form-control' name='new_quantity' id='new_quantity_" . $row['id'] . "' required>";
    echo "</div>";
    echo "</div>";

    echo "<div class='form-group row'>";
    echo "<label for='new_price' class='col-sm-2 col-form-label'>New Price:</label>";
    echo "<div class='col-sm-4'>";
    echo "<input type='text' class='form-control' name='new_price' id='new_price_" . $row['id'] . "' required>";
    echo "</div>";
    echo "</div>";

    echo "<div class='form-group row'>";
    echo "<label for='image' class='col-sm-2 col-form-label'>New Image:</label>";
    echo "<div class='col-sm-4'>";
    echo "<input type='file' class='form-control' name='image' id='image_" . $row['id'] . "'>";
    echo "</div>";
    echo "</div>";

    echo "<button type='button' class='btn btn-primary btn-sm' onclick='showUpdateSection(" . $row['id'] . ")'>Update</button>";
    echo "<button type='submit' class='btn btn-primary btn-sm' style='display: none;' id='update_button_" . $row['id'] . "'>Save</button>";
    echo "</form>";

    echo "<form action='' method='POST' style='display: inline;'>";
    echo "<input type='hidden' name='delete_id' value='" . $row['id'] . "'>";
    echo "<button type='submit' class='btn btn-danger btn-sm'>Delete</button>";
    echo "</form>";

    echo "</div>";
    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Inventory System</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="POS.php">POS</a>
                </li>
            </ul>
        </nav>

        <h2>Add New Product</h2>
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

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showUpdateSection(id) {
            document.getElementById('new_name_' + id).style.display = 'block';
            document.getElementById('new_quantity_' + id).style.display = 'block';
            document.getElementById('new_price_' + id).style.display = 'block';
            document.getElementById('image_' + id).style.display = 'block';
            document.getElementById('update_button_' + id).style.display = 'inline';
        }
    </script>
</body>
</html>
