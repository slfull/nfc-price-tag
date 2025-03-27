<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // 確保 ID 是數字

    include("connection.php");

    $sql = "DELETE FROM products WHERE id=$id";

    if ($con->query($sql) === TRUE) {
        echo "<script>alert('商品已刪除！'); window.location.href='admin.php';</script>";
        exit();
    } else {
        echo "<script>alert('刪除商品失敗：" . $con->error . "'); window.location.href='admin.php';</script>";
        exit();
    }

    $con->close();
} else {
    die("未提供有效的商品 ID");
}
