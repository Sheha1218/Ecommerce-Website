<?php
session_start();

require_once 'Database.php';
require_once 'Cart.php';
require_once 'Order.php';

$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);

$cart->user_id = $_SESSION['user_id'];
$cartItems = $cart->getCartItems();

$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Cart</title>
    <link href="css/customer_cart_styles.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
    
</head>
<body>
    <div class="customer-index">
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
                            <a href="customer_orders.php">Order Details</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <div class="main-content">
            <h1>Your Cart</h1>
            <?php if (isset($_SESSION['message'])): ?>
                <script>
                    showAlert("<?php echo $_SESSION['message']; ?>");
                </script>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            
            <div class="product-items">
                <?php while ($row = $cartItems->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php
                    $order = new Order($db);
                    $order->cart_id = $row['id'];
                    $orderPlaced = $order->isOrderPlaced();
                    ?>
                    <div class="product-item">
                        <div class="product-item-details">
                            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                        </div>
                        <div>Price: <?php echo htmlspecialchars(number_format($row['product_price'], 2)); ?></div>
                        <div>Quantity: <?php echo htmlspecialchars($row['quantity']); ?></div>
                        <div>Total Price: <?php echo htmlspecialchars(number_format($row['product_price'] * $row['quantity'], 2)); ?></div>
                        <div>Status: <?php echo $orderPlaced ? 'Order placed successfully' : 'Still not placed an order'; ?></div>
                        <div class="product-item-actions">
                            <?php if (!$orderPlaced): ?>
                                <form action="delete_from_cart.php" method="post">
                                    <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                                    <button class="cancel" type="submit">Cancel</button>
                                </form>
                                <form action="place_order.php" method="post">
                                    <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                                    <button class="order" type="submit">Order</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php $totalPrice += $row['product_price'] * $row['quantity']; ?>
                <?php endwhile; ?>
            </div>
            
            <div class="total-section">
                <h3>Total: <?php echo htmlspecialchars(number_format($totalPrice, 2)); ?></h3>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Cyber-Deals. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
