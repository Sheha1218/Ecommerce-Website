<?php
include_once 'Database.php';
include_once 'Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 10;
$from_record_num = ($records_per_page * $page) - $records_per_page;
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin_styles.css" rel="stylesheet">
    <style>
        .page-link {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">View Products</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Main Category</th>
                    <th>Sub Category</th>
                    <th>Quantity</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['mainCategory']; ?></td>
                        <td><?php echo $row['subCategory']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><img src="<?php echo $row['image']; ?>" class="img-fluid" style="max-width: 100px;" alt="<?php echo $row['name']; ?>"></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php
        $total_rows = $product->count();
        if ($total_rows > $records_per_page) {
            echo "<ul class='pagination'>";
            $total_pages = ceil($total_rows / $records_per_page);
            if ($page > 1) {
                echo "<li class='page-item'><a class='page-link' href='view_products.php?page=".($page - 1)."'>Previous</a></li>";
            }
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<li class='page-item ".($i == $page ? 'active' : '')."'><a class='page-link' href='view_products.php?page=".$i."'>".$i."</a></li>";
            }
            if ($page < $total_pages) {
                echo "<li class='page-item'><a class='page-link' href='view_products.php?page=".($page + 1)."'>Next</a></li>";
            }
            echo "</ul>";
        }
        ?>
    </div>
</body>
</html>
