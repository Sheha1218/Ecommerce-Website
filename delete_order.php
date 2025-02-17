<?php
include_once 'Database.php';
include_once 'Order.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

$stmt = $order->read();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$alertClass = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['id'])) {
        $order->id = $_POST['id'];
        
        if ($order->delete()) {
            $message = "Order deleted successfully.";
            $alertClass = "alert-success";
            
            $stmt = $order->read();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $message = "Unable to delete order.";
            $alertClass = "alert-danger";
        }
    }
}

if (isset($_GET['fetch_order']) && isset($_GET['id'])) {
    $order->id = $_GET['id'];
    $details = $order->getOrderById();
    echo json_encode($details);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Order</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="css/admin_styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container">
        <?php if (!empty($message)): ?>
        <div class="alert <?php echo $alertClass; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <h2>Delete Order</h2>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="order-id">Select Order Id to Delete:</label>
                <select name="id" id="order-id" class="form-control">
                    <option value="">Select Order ID</option>
                    <?php foreach ($orders as $order): ?>
                        <option value="<?php echo htmlspecialchars($order['id']); ?>"><?php echo htmlspecialchars($order['id']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        
            <div class="form-group">
                <label for="order-user_name">Customer Name:</label>
                <input type="text" id="order-user_name" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="order-user_email">Customer Email:</label>
                <input type="text" id="order-user_email" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="order-product_name">Product Name:</label>
                <input type="text" id="order-product_name" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="order-product_price">Product Price:</label>
                <input type="number" id="order-product_price" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="order-quantity">Quantity:</label>
                <input type="number" id="order-quantity" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="order-total_price">Total Price:</label>
                <input type="number" id="order-total_price" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-danger">Delete Order</button><br><br>
        </form>

        <a href="view_orders.php" class="btn btn-primary">Back to Orders</a>
    </div>

    <script>
        $(document).ready(function() {
            $('#order-id').change(function() {
                var orderID = $(this).val();
                if (orderID) {
                    $.ajax({
                        url: '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>',
                        type: 'GET',
                        data: {
                            fetch_order: 1,
                            id: orderID
                        },
                        success: function(data) {
                            var order = JSON.parse(data);
                            $('#order-user_name').val(order.user_name);
                            $('#order-user_email').val(order.user_email);
                            $('#order-product_name').val(order.product_name);
                            $('#order-product_price').val(order.product_price);
                            $('#order-quantity').val(order.quantity);
                            $('#order-total_price').val(order.total_price);
                        }
                    });
                } else {
                    $('#order-user_name').val('');
                    $('#order-user_email').val('');
                    $('#order-product_name').val('');
                    $('#order-product_price').val('');
                    $('#order-quantity').val('');
                    $('#order-total_price').val('');
                }
            });
        });
    </script>
</body>
</html>
