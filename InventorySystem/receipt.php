<?php
$db_username = 'root';
$db_password = '';
$conn = new PDO('mysql:host=localhost;dbname=inventory', $db_username, $db_password);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Receipt</h1>

        <?php

        if (isset($_GET['product_id']) && isset($_GET['quantity']) && isset($_GET['total'])) {
            $product_id = $_GET['product_id'];
            $quantity = $_GET['quantity'];
            $total = $_GET['total'];

            try {
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = "SELECT * FROM products WHERE id = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id', $product_id);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    $name = $product['name'];
                    $price = $product['price'];

                    echo "<div class='alert alert-success'>";
                    echo "Product: $name<br>";
                    echo "Price: $price<br>";
                    echo "Quantity: $quantity<br>";
                    echo "Total: $total<br>";
                    echo "</div>";
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo "Product not found!";
                    echo "</div>";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        } else {
            echo "<div class='alert alert-danger'>";
            echo "Invalid receipt data!";
            echo "</div>";
        }
        ?>

        <button class="btn btn-primary" onclick="printReceipt()">Print</button>
        <script>
            function printReceipt() {
                window.print();
            }
        </script>
        <a href="POS.php" class="btn btn-secondary">Back to Point of Sale</a>
    </div>
</body>
</html>
