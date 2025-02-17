<?php
session_start();

include_once 'Database.php';
include_once 'Product.php';
include_once 'Feedback.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$feedback = new Feedback($db);

$productQuery = "SELECT * FROM products ORDER BY id DESC";
$stmtProducts = $db->prepare($productQuery);
$stmtProducts->execute();
$num = $stmtProducts->rowCount();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    if (isset($_SESSION['user_id'])) {
        $feedback->user_id = $_SESSION['user_id'];
        $feedback->feedback = htmlspecialchars(strip_tags($_POST['feedback']));
        $feedback->rating = htmlspecialchars(strip_tags($_POST['rating']));
        
        if ($feedback->addFeedback()) {
            echo "<script>alert('Feedback submitted successfully!');</script>";
        } else {
            echo "<script>alert('Failed to submit feedback.');</script>";
        }
    } else {
        echo "<script>alert('You need to be logged in to submit feedback.');</script>";
    }
}

$feedbackQuery = "SELECT * FROM feedbacks ORDER BY rating DESC LIMIT 6";
$stmtFeedbacks = $db->prepare($feedbackQuery);
$stmtFeedbacks->execute();
$feedbacks = $stmtFeedbacks->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $db = new Database();
    $conn = $db->getConnection();
    $product = new Product($conn);

    $stmt = $product->searchProducts($query);

    echo '<div class="search-results">';
    echo '<button class="close-btn" onclick="closeSearchResults()">Close</button>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="product">';
        echo '<h2>' . htmlspecialchars($row['name']) . '</h2>';
        echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" />';
        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
        echo '<p>Price: $' . htmlspecialchars($row['price']) . '</p>';
        echo '<p>Category: ' . htmlspecialchars($row['mainCategory']) . '</p>';
        echo '<p>Subcategory: ' . htmlspecialchars($row['subCategory']) . '</p>';
        echo '<p>Quantity: ' . htmlspecialchars($row['quantity']) . '</p>';
        echo '</div>';
    }
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber-Deals</title>
    <link href="css/index.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="customer-index">
        <nav class="navbar">
            <div class="container">
                <a href="#" class="brand">Cyber-Deals</a>
                <ul class="nav-links">
                    <li><a href="#product-section">Home</a></li>
                    <li><a href="customer_shop.php">Shop</a></li>
                    <li><a href="customer_register.php">Register</a></li>
                    <?php if (!isset($_SESSION['user_email'])): ?>
                        <li><a href="#" id="login-link">Login</a></li>
                    <?php endif; ?>
                </ul>
                <div class="user-section">
                    <div class="login-reminder-container">
                        <?php if (!isset($_SESSION['user_email'])): ?>
                            <div class="login-reminder">
                                To add products to your cart, first log in.
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($_SESSION['user_email']) && $_SESSION['role'] == 'customer'): ?>
                        <div class="user-email">
                            <?php echo htmlspecialchars($_SESSION['user_email']); ?>
                        </div>
                        <div class="cart-button">
                            <a href="customer_cart.php">Your Cart</a>
                        </div>
                        <div class="logout">
                            <a href="logout.php">Logout</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>


        <div class="search-bar">
            <form action="index.php" method="GET">
                <input type="text" name="query" placeholder="Search for product details..." />
                <button type="submit">Search</button>
            </form>
        </div>
        

        <div class="main-content">
            <h1>Welcome to Cyber-Deals</h1>
            <p>Future of Online Shopping</p>
            <h2>Popular Categories</h2>
        </div>


        <div class="product-section">
            <div class="product-container">
                <div class="product-item">
                    <div class="product-label">Mobile Phones</div>
                    <img src="img/phone1.png" alt="Mobile Phones">
                    <a href="customer_mobile.php" class="shop-now-btn">SHOP NOW</a>
                </div>
                <div class="product-item">
                    <div class="product-label">Laptops</div>
                    <img src="img/laptop1.png" alt="Laptops">
                    <a href="customer_laptop.php" class="shop-now-btn">SHOP NOW</a>
                </div>
                <div class="product-item">
                    <div class="product-label">Desktops</div>
                    <img src="img/desktop1.png" alt="Desktops">
                    <a href="customer_desktop.php" class="shop-now-btn">SHOP NOW</a>
                </div>
            </div>
        </div>
        

        <div class="latest-products-section">
            <h2>Latest Products</h2>
            <div class="latest-products-container">
                <?php while ($row = $stmtProducts->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="latest-product-item">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                        <div class="product-info">
                            <h3><?php echo $row['name']; ?></h3>
                            <p><?php echo $row['description']; ?></p>
                            <p>Rs. <?php echo $row['price']; ?></p>
                            <?php if (isset($_SESSION['user_email'])): ?>
                                <form id="add-to-cart-form-<?php echo $row['id']; ?>" action="add_to_cart.php" method="post" class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                    <input type="hidden" name="page_url" value="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="number" name="quantity" min="1" value="1" required class="quantity-input">
                                    <button type="submit" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div> 

        
        <div class="add-feedback">
            <h2>Leave Your Feedback</h2>
            <form id="feedback-form" class="feedback-form">
                <div class="form-group">
                    <label for="feedback">Your Feedback:</label>
                    <textarea name="feedback" id="feedback" class="form-control" placeholder="Write your feedback here..." required></textarea>
                </div>
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <select name="rating" id="rating" class="form-control" required>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit_feedback" class="btn-feedback">Submit Feedback</button>
                </div>
            </form>
        </div>


        <div class="contact" id="contact">
            <h1>Contact Us</h1>
            <div class="contact-us">
                <p>If you have any questions, concerns, or need assistance, please feel free to reach out to us. Our dedicated support team is here to help you!</p>
                <ul class="contact-list">
                    <li><strong>Email:</strong>  support@cyber-deals.com</li>
                    <li><strong>Phone:</strong>  011 000 0000</li>
                    <li><strong>Address:</strong>  123 Tech Avenue, Silicon Valley, CA, 94043</li>
                </ul>
                <p>Our customer support team is available Monday to Friday, from 9 AM to 6 PM PST. We strive to respond to all inquiries within 24 hours.</p>
            </div>
        </div>                        
        

        <div class="feedback-section">
            <h2>Customer Feedbacks</h2>
            <div class="feedback-container">
                <div class="feedback-display">
                    <?php foreach ($feedbacks as $feedback): ?>
                        <div class="feedback-item">
                            <p class="feedback-content"> <strong> Feedback: </strong><?php echo htmlspecialchars($feedback['feedback']); ?></p>
                            <p class="feedback-rating"> <strong> Rating: </strong> <?php echo htmlspecialchars($feedback['rating']); ?></p>
                            <p class="feedback-user"> <strong>  User: </strong> <?php echo htmlspecialchars($feedback['user_email']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


        <div id="loginModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="loginOptions">
                    <button id="customerLoginBtn" class="selection-button">Customer Login</button>
                    <button id="staffLoginBtn" class="selection-button">Staff Login</button>
                </div>
                <form id="customerLoginForm" class="login-form" action="login.php" method="post" style="display: none;">
                    <h2>Customer Login</h2>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" value="Login" class="sctbtn">
                </form>
                <form id="staffLoginForm" class="login-form" action="login.php" method="post" style="display: none;">
                    <h2>Staff Login</h2>
                    <select name="role" required>
                        <option value="admin">Admin</option>
                        <option value="processing team">Processing Team</option>
                    </select>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" value="Login" class="sctbtn">
                </form>
            </div>
        </div>
        

    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Cyber-Deals. All Rights Reserved.</p>
        </div>
    </footer>

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

            $("#feedback-form").on("submit", function(e) {
                e.preventDefault();

                var isLoggedIn = <?php echo isset($_SESSION['user_email']) ? 'true' : 'false'; ?>;
                if (!isLoggedIn) {
                    alert("Please login to submit your feedback.");
                    return;
                }

                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: "add_feedback.php",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        alert(response.message);
                        if (response.status) {
                            form[0].reset();
                        }
                    },
                    error: function() {
                        alert("An error occurred while submitting the feedback.");
                    }
                });
            });

            var loginLink = document.getElementById('login-link');
            var modal = document.getElementById('loginModal');
            var span = document.getElementsByClassName('close')[0];
            var customerLoginBtn = document.getElementById('customerLoginBtn');
            var staffLoginBtn = document.getElementById('staffLoginBtn');
            var customerLoginForm = document.getElementById('customerLoginForm');
            var staffLoginForm = document.getElementById('staffLoginForm');

            loginLink.onclick = function() {
                modal.style.display = 'block';
            }

            span.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            customerLoginBtn.onclick = function() {
                customerLoginForm.style.display = 'block';
                staffLoginForm.style.display = 'none';
            }

            staffLoginBtn.onclick = function() {
                staffLoginForm.style.display = 'block';
                customerLoginForm.style.display = 'none';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('.latest-products-container');
        const items = container.innerHTML;
        container.innerHTML += items;
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const feedbackItems = document.querySelectorAll('.feedback-item');
            let currentFeedbackIndex = 0;

            function showFeedbacks() {
                feedbackItems.forEach((item, index) => {
                    item.style.display = 'none';
                });

                for (let i = 0; i < 3; i++) {
                    let index = (currentFeedbackIndex + i) % feedbackItems.length;
                    feedbackItems[index].style.display = 'block';
                }

                currentFeedbackIndex = (currentFeedbackIndex + 3) % feedbackItems.length;
            }

            showFeedbacks();

            setInterval(showFeedbacks, 3000);
        });
    </script>

    <script>
            function closeSearchResults() {
                document.querySelector('.search-results').style.display = 'none';
                document.body.classList.remove('blur');
                window.history.pushState({}, document.title, window.location.pathname);
            }
    </script>

</body>
</html>