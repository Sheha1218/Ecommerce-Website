<?php
session_start();
include_once 'Database.php';
include_once 'Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 10;
$from_record_num = ($records_per_page * $page) - $records_per_page;

$productQuery = "SELECT * FROM products WHERE subCategory = 'phone' LIMIT ?, ?";
$stmtProducts = $db->prepare($productQuery);
$stmtProducts->bindParam(1, $from_record_num, PDO::PARAM_INT);
$stmtProducts->bindParam(2, $records_per_page, PDO::PARAM_INT);
$stmtProducts->execute();
$num = $stmtProducts->rowCount();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Phones - Cyber-Deals</title>
    <link href="css/customer_styles.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <a href="#" class="brand">Cyber-Deals</a>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="customer_shop.php">Shop</a></li>
                    <li><a href="customer_register.php">Register</a></li>
                </ul>
            </div>
            <div class="login-reminder-container">
                <?php if (!isset($_SESSION['user_email'])): ?>
                    <div class="login-reminder">
                        To add products to your cart, first log in.
                    </div>
                <?php endif; ?>
            </div>
            <?php if (isset($_SESSION['user_email'])): ?>
                <div class="user-email">
                    <?php echo htmlspecialchars($_SESSION['user_email']); ?>
                </div>
                <div class="cart-button">
                    <a href="customer_cart.php">Your Cart</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>


    <div class="main-content">
            <div class="product-grid">
                <?php while ($row = $stmtProducts->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="product-item">
                        <div class="product-img">
                            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                        </div>
                        <div class="product-details">
                            <h3 class="product-name"><?php echo $row['name']; ?></h3>
                            <p class="product-price">Rs. <?php echo $row['price']; ?></p>
                            <p class="product-description"><?php echo $row['description']; ?></p>
                            <?php if (isset($_SESSION['user_email'])): ?>
                                <form id="add-to-cart-form-<?php echo $row['id']; ?>" action="add_to_cart.php" method="post" class="add-to-cart-form" data-page-url="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                    <input type="number" name="quantity" min="1" value="1" required class="quantity-input">
                                    <button type="submit" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <footer class="footer bg-light py-3 mt-auto">
        <div class="container">
            <p class="text-center">&copy; 2024 Cyber-Deals. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".add-to-cart-form").on("submit", function(e) {
                e.preventDefault();

                var form = $(this);
                var formData = form.serialize();
                var actionUrl = form.attr("action");

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert("An error occurred while adding the product to the cart.");
                    }
                });
            });
        });
    </script>

</body>
</html>
