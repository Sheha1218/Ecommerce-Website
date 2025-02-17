<?php
include_once 'Database.php';
include_once 'Order.php';
include_once 'User.php';
include_once 'Product.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);
$stmt = $order->read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin_styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">View Orders</h1>
        <?php if ($stmt->rowCount() > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <?php
                        $user = new User($db);
                        $user->id = $row['user_id'];
                        $user_detail = $user->getUserById();

                        $product = new Product($db);
                        $product->id = $row['product_id'];
                        $product_detail = $product->getProductById();
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($user_detail['name']); ?></td>
                            <td><?php echo htmlspecialchars($user_detail['email']); ?></td>
                            <td><?php echo htmlspecialchars($product_detail['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No orders found.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
