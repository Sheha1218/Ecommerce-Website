<?php
include_once 'Database.php';
include_once 'Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$message = "";

$product_detail = [
    'id' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'mainCategory' => '',
    'subCategory' => '',
    'quantity' => '',
    'image' => '',
];

$products = $product->getAllProducts();
$categories = [
    'main' => ['mobile', 'computer'],
    'sub' => [
        'phone', 'tablet', 'charger', 'earbud', 'headphone', 'cable',
        'desktop', 'laptop', 'motherboard', 'processor', 'ram', 'storage', 'casing'
    ]
];

if ($_POST) {
    $product->id = $_POST['id'];
    $product->name = $_POST['name'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];
    $product->mainCategory = $_POST['mainCategory'];
    $product->subCategory = $_POST['subCategory'];
    $product->quantity = $_POST['quantity'];
    $product->image = $_POST['current_image'];

    if (!empty($_FILES['image']['name'])) {
        $uploaded_image = $product->uploadImage($_FILES['image']);
        if ($uploaded_image) {
            $product->image = $uploaded_image;
        } else {
            $message = "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
        }
    }

    if ($product->update()) {
        $message = "<div class='alert alert-success'>Product updated successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Unable to update product.</div>";
    }
} elseif (isset($_GET['id'])) {
    $product->id = $_GET['id'];
    $product_detail = $product->getProductById();

    if (!$product_detail) {
        $message = "<div class='alert alert-danger'>Product not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin_styles.css" rel="stylesheet">
    <script>
        function previewImage() {
            const file = document.getElementById('image').files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('currentImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <?php echo $message; ?>
        <h1 class="mt-5">Update Product Details</h1>
        
        <form action="update_product.php" method="get" id="productSelectForm">
            <div class="mb-3">
                <label for="productSelect" class="form-label">Select Product</label>
                <select class="form-control" id="productSelect" name="id" onchange="document.getElementById('productSelectForm').submit();">
                    <option value="">Select a product</option>
                    <?php foreach ($products as $product_item) { ?>
                        <option value="<?php echo htmlspecialchars($product_item['id']); ?>" <?php echo $product_item['id'] == $product_detail['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($product_item['name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </form>

        <form action="update_product.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="id" class="form-label">Product ID</label>
                <input type="text" class="form-control" id="id" name="id" value="<?php echo htmlspecialchars($product_detail['id']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product_detail['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Product Description</label>
                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($product_detail['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Product Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product_detail['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="mainCategory" class="form-label">Main Category</label>
                <select class="form-control" id="mainCategory" name="mainCategory" required>
                    <option value="">Select a main category</option>
                    <?php foreach ($categories['main'] as $mainCategory) { ?>
                        <option value="<?php echo htmlspecialchars($mainCategory); ?>" <?php echo $mainCategory == $product_detail['mainCategory'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($mainCategory); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="subCategory" class="form-label">Sub Category</label>
                <select class="form-control" id="subCategory" name="subCategory" required>
                    <option value="">Select a sub category</option>
                    <?php foreach ($categories['sub'] as $subCategory) { ?>
                        <option value="<?php echo htmlspecialchars($subCategory); ?>" <?php echo $subCategory == $product_detail['subCategory'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($subCategory); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product_detail['quantity']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" onchange="previewImage()">
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product_detail['image']); ?>">
                <?php if (!empty($product_detail['image'])): ?>
                    <img id="currentImage" src="<?php echo htmlspecialchars($product_detail['image']); ?>" alt="Product Image" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
        <a href="view_products.php" class="btn btn-primary mt-3">Back to Product Details</a><br><br>
    </div>
</body>
</html>
