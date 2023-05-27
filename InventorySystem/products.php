<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Products</h1>
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
            </ul>
        </nav>
        <h2>Data Analytics</h2>

        <?php
        $db_username = 'root';
        $db_password = '';
        $conn = new PDO('mysql:host=localhost;dbname=inventory', $db_username, $db_password);

        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Calculate data analytics
            $queryGrossIncome = "SELECT SUM(quantity * price) AS gross_income FROM sales";
            $resultGrossIncome = $conn->query($queryGrossIncome);
            $grossIncome = $resultGrossIncome->fetch(PDO::FETCH_ASSOC)['gross_income'];

            $queryNetIncome = "SELECT SUM(quantity * price) AS net_income FROM sales";
            $resultNetIncome = $conn->query($queryNetIncome);
            $netIncome = $resultNetIncome->fetch(PDO::FETCH_ASSOC)['net_income'];

            $queryTotalSales = "SELECT SUM(quantity) AS total_sales FROM sales";
            $resultTotalSales = $conn->query($queryTotalSales);
            $totalSales = $resultTotalSales->fetch(PDO::FETCH_ASSOC)['total_sales'];
            ?>

            <div class="analytics-container">
                <div class="analytics-item">
                    <h3>Income</h3>
                    <p><?php echo '₱' . number_format($netIncome, 2); ?></p>
                </div>
                <div class="analytics-item">
                    <h3>Total Sales</h3>
                    <p><?php echo $totalSales; ?></p>
                </div>
            </div>

            <?php
            $queryProducts = "SELECT * FROM products";
            $resultProducts = $conn->query($queryProducts);

            if ($resultProducts->rowCount() > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Product ID</th><th>Product Name</th><th>Quantity</th><th>Price</th><th>Image</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $resultProducts->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>$" . number_format($row['price'], 2) . "</td>";
                    echo "<td>";
                    if ($row['image']) {
                        echo "<img src='images/" . $row['image'] . "' style='width: 100px; height: 100px;' />";
                    }
                    echo "</td>";
                    echo "<td>";
                    echo "<a href='update_product.php?id=" . $row['id'] . "' class='btn btn-primary'>Update</a> ";
                    echo "<a href='delete_product.php?id=" . $row['id'] . "' class='btn btn-danger'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "No products found.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>

    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
