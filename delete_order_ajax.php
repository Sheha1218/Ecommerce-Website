<?php
session_start();
require_once 'Database.php';
require_once 'Order.php';

$response = ['success' => false];

if (isset($_POST['order_id']) && isset($_SESSION['user_id'])) {
    $database = new Database();
    $db = $database->getConnection();

    $order = new Order($db);
    $order->id = $_POST['order_id'];
    $order->user_id = $_SESSION['user_id'];

    $orderDetails = $order->getOrderById();
    if ($orderDetails && $orderDetails['user_id'] == $order->user_id) {
        if ($order->deleteOrderByUser()) {
            $response['success'] = true;
        }
    }
}

echo json_encode($response);
?>
