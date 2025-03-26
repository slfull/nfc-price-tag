<?php
include("connection.php");

$sql = "SELECT * FROM products";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>商品列表</title>
</head>
<body>
    <h1>商品列表</h1>
    <a href="add_product.php">新增商品</a>
    <table border="1">
        <tr>
            <th>名稱</th>
            <th>價格</th>
            <th>描述</th>
            <th>進貨日期</th>
            <th>有效日期</th>
            <th>操作</th>
        </tr>

        <?php
        while($product = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $product['name'] . "</td>";
            echo "<td>" . $product['price'] . "</td>";
            echo "<td>" . $product['description'] . "</td>";
            echo "<td>" . $product['stock_date'] . "</td>";
            echo "<td>" . $product['expiry_date'] . "</td>";
            echo "<td>
                <a href='update_product.php?id=" . $product['id'] . "'>更新</a> |
                <a href='delete_product.php?id=" . $product['id'] . "'>刪除</a>
            </td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$con->close();
?>