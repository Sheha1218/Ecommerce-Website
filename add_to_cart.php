<?php
session_start();
include_once 'Database.php';
include_once 'Cart.php';

$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);

$response = ["status" => false, "message" => "Failed to add product to cart"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cart->product_id = $_POST['product_id'];
    $cart->product_name = $_POST['product_name'];
    $cart->product_price = $_POST['product_price'];
    $cart->quantity = $_POST['quantity'];
    $cart->user_id = $_SESSION['user_id'];

    if ($cart->addProduct()) {
        $response["status"] = true;
        $response["message"] = "Product added to cart successfully";
    }
}

echo json_encode($response);
?>
