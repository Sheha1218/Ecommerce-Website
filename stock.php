<?php
include_once 'Database.php';
include_once 'Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$mainCategory = isset($_GET['mainCategory']) ? $_GET['mainCategory'] : '';
$subCategory = isset($_GET['subCategory']) ? $_GET['subCategory'] : '';

$mainCategories = $db->query("SELECT DISTINCT mainCategory FROM products")->fetchAll(PDO::FETCH_ASSOC);
$subCategories = $db->query("SELECT DISTINCT subCategory FROM products WHERE mainCategory LIKE '%$mainCategory%'")->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT name, quantity, price FROM products WHERE mainCategory LIKE ? AND subCategory LIKE ?";
$stmt = $db->prepare($query);
$mainCategoryParam = $mainCategory ? $mainCategory : '%';
$subCategoryParam = $subCategory ? $subCategory : '%';
$stmt->execute([$mainCategoryParam, $subCategoryParam]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/stock_styles.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <form action="stock.php" method="GET">
            <div class="mb-3">
                <label for="mainCategory" class="form-label">Main Category</label>
                <select id="mainCategory" name="mainCategory" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <?php foreach ($mainCategories as $category): ?>
                        <option value="<?php echo $category['mainCategory']; ?>" <?php if ($mainCategory == $category['mainCategory']) echo 'selected'; ?>>
                            <?php echo $category['mainCategory']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="subCategory" class="form-label">Sub Category</label>
                <select id="subCategory" name="subCategory" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <?php foreach ($subCategories as $category): ?>
                        <option value="<?php echo $category['subCategory']; ?>" <?php if ($subCategory == $category['subCategory']) echo 'selected'; ?>>
                            <?php echo $category['subCategory']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <div class="content">
        <h1 class="mt-5">Stock Management</h1>
        <form action="download_stock_report.php" method="POST">
            <input type="hidden" name="mainCategory" value="<?php echo $mainCategory; ?>">
            <input type="hidden" name="subCategory" value="<?php echo $subCategory; ?>">
            <button type="submit" class="btn btn-primary mb-3">Get Report</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['quantity']; ?></td>
                            <td><?php echo $product['price']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
