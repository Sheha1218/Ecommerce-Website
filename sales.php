<?php
include_once 'Database.php';
include_once 'Order.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);
$stmt = $order->read();

$totalSales = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin_styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Sales Report</h1>
        <form action="download_sales_report.php" method="POST">
            <button type="submit" class="btn btn-primary mb-3">Get Report</button>
        </form>
        <?php if ($stmt->rowCount() > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <?php
                        $totalSales += $row['total_price'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total Sales</strong></td>
                        <td><strong><?php echo htmlspecialchars($totalSales); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No sales found.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
