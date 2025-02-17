<?php
include_once 'Database.php';
include_once 'Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$mainCategory = isset($_POST['mainCategory']) ? $_POST['mainCategory'] : '';
$subCategory = isset($_POST['subCategory']) ? $_POST['subCategory'] : '';

$query = "SELECT name, quantity, price FROM products WHERE mainCategory LIKE ? AND subCategory LIKE ?";
$stmt = $db->prepare($query);
$mainCategoryParam = $mainCategory ? $mainCategory : '%';
$subCategoryParam = $subCategory ? $subCategory : '%';
$stmt->execute([$mainCategoryParam, $subCategoryParam]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($products)) {

    $filename = "stock_report_" . date('Ymd') . ".csv";
    $file = fopen('php://output', 'w');

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    fputcsv($file, ['Name', 'Quantity', 'Price']);

    foreach ($products as $product) {
        fputcsv($file, $product);
    }

    fclose($file);
    exit();
    
} else {
    echo "No products found.";
}
?>
