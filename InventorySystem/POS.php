<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Point of Sale</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Point of Sale</h1>

        <?php
        $db_username = 'root';
        $db_password = '';
        $conn = new PDO('mysql:host=localhost;dbname=inventory', $db_username, $db_password);

        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the form is submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Retrieve form data
                $product_id = $_POST['product_id'];
                $quantity = $_POST['quantity'];

                // Retrieve product information from the database
                $query = "SELECT * FROM products WHERE id = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id', $product_id);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    $name = $product['name'];
                    $price = $product['price'];

                    if ($product['quantity'] >= $quantity) {
                        // Calculate total and deduct quantity from inventory
                        $total = $price * $quantity;
                        $newQuantity = $product['quantity'] - $quantity;

                        // Update the quantity in the database
                        $updateQuery = "UPDATE products SET quantity = :newQuantity WHERE id = :id";
                        $updateStmt = $conn->prepare($updateQuery);
                        $updateStmt->bindParam(':newQuantity', $newQuantity);
                        $updateStmt->bindParam(':id', $product_id);
                        $updateStmt->execute();

                        if ($product['quantity'] >= $quantity) {
                            // Calculate total and deduct quantity from inventory
                            $total = $price * $quantity;
                            $newQuantity = $product['quantity'] - $quantity;
                        
                            // Update the quantity in the database
                            // ...
                        
                            // Redirect to the receipt page with data
                            $redirectUrl = "receipt.php?product_id=$product_id&quantity=$quantity&total=$total";
                            header("Location: $redirectUrl");
                            exit();
                        }
                        
                    } else {
                        echo "<div class='alert alert-danger'>";
                        echo "Insufficient quantity available!";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo "Product not found!";
                    echo "</div>";
                }
            }

            $query = "SELECT * FROM products";
            if(isset($_GET['search'])) {
                $searchTerm = $_GET['search'];
                $query .= " WHERE name LIKE '%$searchTerm%'";
            }
            
            $result = $conn->query($query);

            if ($result->rowCount() > 0) {
                echo "<h2>Available Products</h2>";
 
                echo "<form action='' method='GET' class='mb-3'>";
                echo "<div class='form-group'>";
                echo "<label for='search'>Search by Name:</label>";
                echo "<input type='text' class='form-control' name='search' id='search' placeholder='Enter product name'>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>Search</button>";
                echo "</form>";
                
                echo "<table class='table'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Name</th>";
                echo "<th>Quantity</th>";
                echo "<th>Price</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "</tr>";
                }
                
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<div class='alert alert-info'>No products available.</div>";
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        ?>

        <h2>Sell Product</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="product_id">Product ID:</label>
                <input type="text" class="form-control" name="product_id" id="product_id" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" name="quantity" id="quantity" required>
            </div>

            <button type="submit" class="btn btn-primary" name="print">Sell</button>
        </form>
        
        <br>
        <a href="home.php" class="btn btn-secondary">Back to Inventory System</a>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function printReceipt() {
            window.print();
        }
    </script>
</body>
</html>
