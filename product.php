<?php
include("connection.php");

if ($con->connect_error) {
    die("連接失敗：" . $con->connect_error);
}

if (!isset($_GET['id'])) {
    die("請提供商品 ID");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $id";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    die("找不到該商品");
}

$con->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?></title>
</head>
<body>
    <h1><?php echo $product['name']; ?></h1>
    <p>價格：NT$ <?php echo $product['price']; ?></p>
    <p>到期日：<?php echo $product['expiry_date']; ?></p>
    <p>進貨日：<?php echo $product['stock_date']; ?></p>
    <p>商品訊息：<?php echo $product['description']; ?></p>
    
</body>
</html>
