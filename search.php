<?php
include 'Database.php';
include 'Product.php';

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
