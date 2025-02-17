<?php
session_start();

require_once 'Database.php';
require_once 'Order.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);

$order->user_id = $_SESSION['user_id'];
$orders = $order->getOrdersByUserId();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Orders</title>
    <link href="css/customer_orders_styles.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function deleteOrder(orderId) {
            if (confirm("Are you sure you want to delete this order?")) {
                $.ajax({
                    url: 'delete_order_ajax.php',
                    type: 'POST',
                    data: {
                        order_id: orderId
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            $("#order-" + orderId).remove();
                            alert("Order deleted successfully.");
                        } else {
                            alert("Failed to delete order.");
                        }
                    }
                });
            }
        }
    </script>
</head>
<body>
    <div class="customer-orders">
        <nav class="navbar">
            <div class="container">
                <a href="#" class="brand">Cyber-Deals</a>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="customer_shop.php">Shop</a></li>
                    <li><a href="customer_register.php">Register</a></li>
                    <?php if (!isset($_SESSION['user_email'])): ?>
                        <li><a href="#" id="login-link">Login</a></li>
                    <?php endif; ?>
                </ul>
                <div class="user-section">
                    <?php if (isset($_SESSION['user_email'])): ?>
                        <div class="user-email">
                            <?php echo htmlspecialchars($_SESSION['user_email']); ?>
                        </div>
                        <div class="page-button">
                            <a href="customer_cart.php">Your Cart</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <div class="main-content">
            <h1>Your Orders</h1>
            <?php if ($orders->rowCount() > 0): ?>
                <div class="product-items">
                    <?php while ($row = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="product-item" id="order-<?php echo htmlspecialchars($row['id']); ?>">
                            <div class="product-item-details">
                                <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                                <div>Price: <?php echo htmlspecialchars(number_format($row['product_price'], 2)); ?></div>
                                <div>Quantity: <?php echo htmlspecialchars($row['quantity']); ?></div>
                                <div>Total Price: <?php echo htmlspecialchars(number_format($row['total_price'], 2)); ?></div>
                            </div>
                            <div class="product-item-actions">
                                <button class="cancel" onclick="deleteOrder(<?php echo htmlspecialchars($row['id']); ?>)">Cancel Order</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    No orders found.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Cyber-Deals. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
