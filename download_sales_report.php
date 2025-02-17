<?php
include_once 'Database.php';
include_once 'Order.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);
$stmt = $order->read();

if ($stmt->rowCount() > 0) {

    $filename = "sales_report_" . date('Ymd') . ".csv";
    $file = fopen('php://output', 'w');

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    fputcsv($file, ['Product Name', 'Product Price', 'Quantity', 'Total Price']);

    $totalSales = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $data = [
            'product_name' => $row['product_name'],
            'product_price' => $row['product_price'],
            'quantity' => $row['quantity'],
            'total_price' => $row['total_price']
        ];
        fputcsv($file, $data);
        $totalSales += $row['total_price'];
    }

    fputcsv($file, ['', '', 'Total Sales', $totalSales]);

    fclose($file);
    exit();
    
} else {
    echo "No sales found.";
}
?>
