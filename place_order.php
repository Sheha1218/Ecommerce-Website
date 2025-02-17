<?php
session_start();

require_once 'Database.php';
require_once 'Order.php';
require_once 'Cart.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Please log in to place an order.');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$database = new Database();
$db = $database->getConnection();

if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_email'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name, email FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
    } else {
        echo "<script>
                alert('User details not found.');
                window.location.href = 'index.php';
              </script>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_id'])) {
    $cart = new Cart($db);
    $cart->id = $_POST['cart_id'];
    $cart_item = $cart->getCartItemById();

    if ($cart_item) {
        $order = new Order($db);
        $order->user_id = $_SESSION['user_id'];
        $order->user_name = $_SESSION['user_name'];
        $order->user_email = $_SESSION['user_email'];
        $order->product_id = $cart_item['product_id'];
        $order->cart_id = $cart_item['id'];
        $order->product_name = $cart_item['product_name'];
        $order->product_price = $cart_item['product_price'];
        $order->quantity = $cart_item['quantity'];
        $order->total_price = $cart_item['product_price'] * $cart_item['quantity'];

        if ($order->create()) {
            echo "<script>
                    alert('Order placed successfully.');
                    window.location.href = 'customer_cart.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Unable to place order. Please try again.');
                    window.location.href = 'customer_cart.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Cart item not found.');
                window.location.href = 'customer_cart.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = 'customer_cart.php';
          </script>";
}
?>
