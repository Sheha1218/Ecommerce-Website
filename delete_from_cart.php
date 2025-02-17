<?php
session_start();

require_once 'Database.php';
require_once 'Cart.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cart_id'])) {
        $cart_id = $_POST['cart_id'];

        $database = new Database();
        $db = $database->getConnection();

        $cart = new Cart($db);
        $cart->id = $cart_id;
        $cart->user_id = $_SESSION['user_id']; 

        if ($cart->deleteCartItem()) {
            $_SESSION['message'] = "Item deleted successfully.";
        } else {
            $_SESSION['message'] = "Unable to delete item. Please try again.";
        }

        header("Location: customer_cart.php");
        exit();
    }
}
?>
