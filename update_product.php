<?php
include("connection.php");

// 檢查是否為 POST 請求
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $name = $con->real_escape_string($_POST['name']);
    $price = floatval($_POST['price']);
    $description = $con->real_escape_string($_POST['description']);
    $stock_date = $con->real_escape_string($_POST['stock_date']);
    $expiry_date = $con->real_escape_string($_POST['expiry_date']);

    // 價格不能為負
    if ($price < 0) {
        echo "<script>alert('價格不能為負數，請重新輸入！'); window.history.back();</script>";
        exit();
    }

    // 檢查有效日期不能小於進貨日期
    if (strtotime($expiry_date) < strtotime($stock_date)) {
        echo "<script>alert('有效日期不能小於進貨日期！'); window.history.back();</script>";
        exit();
    }

    $sql = "UPDATE products 
            SET name='$name', price='$price', description='$description', stock_date='$stock_date', expiry_date='$expiry_date' 
            WHERE id=$id";

    if ($con->query($sql) === TRUE) {
        echo "<script>alert('商品更新成功！'); window.location.href='admin.php';</script>";
        exit();
    } else {
        echo "<script>alert('更新失敗：" . $con->error . "');</script>";
    }
}

// 獲取商品資訊
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT * FROM products WHERE id=$id";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("找不到該商品");
    }
} else {
    die("無效的商品 ID");
}

$con->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>更新商品</title>
    <style>
        body {
            font-family: 'Microsoft JhengHei', Arial, sans-serif;
            background-color: #F5F5F5; /* 背景顏色設為 #F5F5F5 */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }

        input:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        textarea {
            height: 100px;
            resize: none;
        }

        .btn {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
            width: 48%;
            margin-top: 10px;
            display: inline-block;
        }

        .btn:hover {
            background: linear-gradient(135deg, #218838, #1e7e34);
            transform: scale(1.05);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            text-decoration: none;
            padding: 12px 20px;
            display: inline-block;
            border-radius: 6px;
            color: white;
            transition: 0.3s;
            font-size: 16px;
            margin-top: 10px;
            width: 48%;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #0056b3, #004099);
            transform: scale(1.05);
        }

        .error {
            color: red;
            font-weight: bold;
        }

    </style>
    <script>
        function validateForm() {
            var price = document.getElementById("price").value;
            var stockDate = document.getElementById("stock_date").value;
            var expiryDate = document.getElementById("expiry_date").value;

            // 檢查價格是否為負數
            if (price < 0) {
                alert("價格不能為負數，請重新輸入！");
                return false;
            }

            // 檢查有效日期是否小於進貨日期
            if (new Date(expiryDate) < new Date(stockDate)) {
                alert("有效日期不能小於進貨日期！");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>更新商品</h1>
        <form method="post" action="update_product.php" onsubmit="return validateForm()">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">

            <label>商品名稱:</label><br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br><br>

            <label>價格:</label><br>
            <input type="number" id="price" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" min="0" required><br><br>

            <label>描述:</label><br>
            <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br><br>

            <label>進貨日期:</label><br>
            <input type="date" id="stock_date" name="stock_date" value="<?php echo htmlspecialchars($product['stock_date']); ?>" required><br><br>

            <label>有效日期:</label><br>
            <input type="date" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($product['expiry_date']); ?>" required><br><br>

            <button type="submit" class="btn">更新商品</button>
            <a href="admin.php" class="btn-secondary">取消</a>
        </form>
    </div>
</body>
</html>
