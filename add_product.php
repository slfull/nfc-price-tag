<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock_date = $_POST['stock_date'];
    $expiry_date = $_POST['expiry_date'];
    include("connection.php");

    $sql = "INSERT INTO products (name, price, description, expiry_date, stock_date) 
            VALUES ('$name', '$price', '$description', '$expiry_date', '$stock_date')";

    if ($con->query($sql) === TRUE) {
        echo "新商品已新增！";
        header("Location: admin.php");
    } else {
        echo "新增商品失敗：" . $con->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>新增商品</title>
</head>
<body>
    <h1>新增商品</h1>
    <form method="post" action="add_product.php">
        <label>商品名稱:</label><br>
        <input type="text" name="name" required><br><br>

        <label>價格:</label><br>
        <input type="number" step="0.01" name="price" required><br><br>

        <label>描述:</label><br>
        <textarea name="description" required></textarea><br><br>

        <label>進貨日期:</label><br>
        <input type="date" name="stock_date" required><br><br>

        <label>有效日期:</label><br>
        <input type="date" name="expiry_date" required><br><br>

        <input type="submit" value="新增商品">
    </form>
</body>
</html>