<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cyber-Deals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin_styles.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <a href="add_product.php" class="btn btn-product" target="_blank">Add Product</i></a>
        <a href="view_products.php" class="btn btn-product" target="_blank">View Product Details</a>
        <a href="update_product.php" class="btn btn-product" target="_blank">Update Product Details</a>
        <a href="delete_product.php" class="btn btn-product" target="_blank">Delete Product</a>
        <a href="add_user.php" class="btn btn-user" target="_blank">Add User</a>
        <a href="view_users.php" class="btn btn-user" target="_blank">View User Details</a>
        <a href="update_user.php" class="btn btn-user" target="_blank">Update User Details</a>
        <a href="delete_user.php" class="btn btn-user" target="_blank">Delete User</a>
        <a href="view_orders.php" class="btn btn-order" target="_blank">View Orders</a>
        <a href="delete_order.php" class="btn btn-order" target="_blank">Delete Order</a>
        <a href="stock.php" class="btn btn-report" target="_blank">Stock Report</a>
        <a href="sales.php" class="btn btn-report" target="_blank">Sales Report</a>
        <a href="index.php" class="btn btn-home" target="">Home Page</a>
    </div>
    <div class="main-content">
        <div class="container" id="content">
            <?php
            include 'Database.php';

            $database = new Database();
            $conn = $database->getConnection();

            $product_count = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch(PDO::FETCH_ASSOC)['count'];
            $user_count = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch(PDO::FETCH_ASSOC)['count'];
            $order_count = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch(PDO::FETCH_ASSOC)['count'];
            ?>
            <div class="dashboard-overview mt-4">
                <h2>Dashboard Overview</h2>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Total Products</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product_count; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Total Users</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $user_count; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-header">Total Orders</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $order_count; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div><br>
            <div class="video-container">
                <video autoplay muted loop>
                    <source src="video/graph.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </div>
</body>
</html>
