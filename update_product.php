<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock_date = $_POST['stock_date'];
    $expiry_date = $_POST['expiry_date'];

    include("connection.php");

    $sql = "UPDATE products 
            SET name='$name', price='$price', description='$description', stock_date='$stock_date', expiry_date='$expiry_date' 
            WHERE id=$id";

    if ($con->query($sql) === TRUE) {
        echo "商品更新成功！";
        header("Location: admin.php");
    } else {
        echo "更新商品失敗：" . $con->error;
    }

    $con->close();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    include("connection.php");

    $sql = "SELECT * FROM products WHERE id=$id";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("找不到該商品");
    }

    $con->close();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>更新商品</title>
</head>
<body>
    <h1>更新商品</h1>
    <form method="post" action="update_product.php">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

        <label>商品名稱:</label><br>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br><br>

        <label>價格:</label><br>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required><br><br>

        <label>描述:</label><br>
        <textarea name="description" required><?php echo $product['description']; ?></textarea><br><br>

        <label>進貨日期:</label><br>
        <input type="date" name="stock_date" value="<?php echo $product['stock_date']; ?>" required><br><br>

        <label>有效日期:</label><br>
        <input type="date" name="expiry_date" value="<?php echo $product['expiry_date']; ?>" required><br><br>

        <input type="submit" value="更新商品">
    </form>
</body>
</html>
