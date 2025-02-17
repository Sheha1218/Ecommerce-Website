<?php
include_once 'Database.php';
include_once 'Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product->name = $_POST['name'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];
    $product->mainCategory = $_POST['mainCategory'];
    $product->subCategory = $_POST['subCategory'];
    $product->quantity = $_POST['quantity'];

    $target_file = $product->uploadImage($_FILES["image"]);
    if ($target_file) {
        $product->image = $target_file;
        if ($product->create()) {
            $message = "<div class='alert alert-success'>Product added successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Unable to add product.<br>Error: " . $product->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Image upload failed. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin_styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php if (!empty($message)) echo $message; ?>
        <h1 class="mt-5">Add Product</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="mainCategory" class="form-label">Main Category</label>
                <select class="form-control" id="mainCategory" name="mainCategory" required>
                    <option value="">Select Main Category</option>
                    <option value="mobile">Mobile</option>
                    <option value="computer">Computer</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="subCategory" class="form-label">Sub Category</label>
                <select class="form-control" id="subCategory" name="subCategory" required>
                    <option value="">Select Sub Category</option>
                    <option value="phone">Phone</option>
                    <option value="tablet">Tablet</option>
                    <option value="charger">Charger</option>
                    <option value="earbud">Earbud</option>
                    <option value="headphone">Headphone</option>
                    <option value="cable">Cable</option>
                    <option value="desktop">Desktop</option>
                    <option value="laptop">Laptop</option>
                    <option value="motherboard">Motherboard</option>
                    <option value="processor">Processor</option>
                    <option value="ram">RAM</option>
                    <option value="storage">Storage</option>
                    <option value="casing">Casing</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
        <br>
    </div>
</body>
</html>
