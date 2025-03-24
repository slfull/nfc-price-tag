<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    include("connection.php");

    $sql = "DELETE FROM products WHERE id=$id";

    if ($con->query($sql) === TRUE) {
        echo "商品已刪除！";
        header("Location: admin.php");
    } else {
        echo "刪除商品失敗：" . $con->error;
    }

    $con->close();
} else {
    die("未提供商品 ID");
}
