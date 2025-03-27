<?php
include("connection.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("請提供有效的商品 ID");
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
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 50%;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p><strong>價格：</strong>NT$ <?php echo htmlspecialchars($product['price']); ?></p>
        <p><strong>到期日：</strong><?php echo htmlspecialchars($product['expiry_date']); ?></p>
        <p><strong>進貨日：</strong><?php echo htmlspecialchars($product['stock_date']); ?></p>
        <p><strong>商品訊息：</strong><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        <a href="admin.php" class="btn">返回商品列表</a>
    </div>
</body>
</html>
