<?php
include_once 'Database.php';
include_once 'Product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$stmt = $product->read();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$alertClass = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['name'])) {
        $product->name = $_POST['name'];
        
        if ($product->delete()) {
            $message = "Product deleted successfully.";
            $alertClass = "alert-success";
            
            $stmt = $product->read();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $message = "Unable to delete product.";
            $alertClass = "alert-danger";
        }
    }
}

if (isset($_GET['fetch_product']) && isset($_GET['name'])) {
    $product->name = $_GET['name'];
    $details = $product->fetchProductDetails();
    echo json_encode($details);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Product</title>
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

        <h2>Delete Product</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="product-name">Select Product Name to Delete:</label>
                <select name="name" id="product-name" class="form-control">
                    <option value="">Select a product</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo htmlspecialchars($product['name']); ?>"><?php echo htmlspecialchars($product['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="product-description">Description:</label>
                <textarea id="product-description" class="form-control" rows="3" readonly></textarea>
            </div>

            <div class="form-group">
                <label for="product-price">Price:</label>
                <input type="text" id="product-price" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="product-main-category">Main Category:</label>
                <input type="text" id="product-main-category" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="product-sub-category">Sub Category:</label>
                <input type="text" id="product-sub-category" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="product-quantity">Quantity:</label>
                <input type="number" id="product-quantity" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="product-image">Image:</label>
                <img id="product-image" class="img-thumbnail" src="" alt="Product Image" style="max-width: 200px;">
            </div>

            <button type="submit" class="btn btn-danger">Delete Product</button><br><br>
        </form>
        
        <a href="view_products.php" class="btn btn-primary">Back to Product Details</a><br><br>
    </div>

    <script>
        $(document).ready(function() {
            $('#product-name').change(function() {
                var productName = $(this).val();
                if (productName) {
                    $.ajax({
                        url: '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>',
                        type: 'GET',
                        data: {
                            fetch_product: 1,
                            name: productName
                        },
                        success: function(data) {
                            var product = JSON.parse(data);
                            $('#product-description').val(product.description);
                            $('#product-price').val(product.price);
                            $('#product-main-category').val(product.mainCategory);
                            $('#product-sub-category').val(product.subCategory);
                            $('#product-quantity').val(product.quantity);
                            $('#product-image').attr('src', product.image);
                        }
                    });
                } else {
                    $('#product-description').val('');
                    $('#product-price').val('');
                    $('#product-main-category').val('');
                    $('#product-sub-category').val('');
                    $('#product-quantity').val('');
                    $('#product-image').attr('src', '');
                }
            });
        });
    </script>
</body>
</html>


