<?php
$error = ""; // 存放錯誤訊息

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // 從 URL 參數中獲取商品資料
    $name = isset($_GET['name']) ? $_GET['name'] : '';
    $price = isset($_GET['price']) ? $_GET['price'] : 0;
    $description = isset($_GET['description']) ? $_GET['description'] : '';
    $stock_date = isset($_GET['stock_date']) ? $_GET['stock_date'] : '';
    $expiry_date = isset($_GET['expiry_date']) ? $_GET['expiry_date'] : '';

    // 檢查價格是否為負數
    if ($price < 0) {
        $error = "價格不能為負數，請重新輸入！";
    } else {
        include("connection.php");

        // 檢查必填欄位是否有空
        if ($name != '' && $price >= 0 && $description != '' && $stock_date != '' && $expiry_date != '') {
            // 檢查有效日期是否小於進貨日期
            if (strtotime($expiry_date) < strtotime($stock_date)) {
                $error = "有效日期不能小於進貨日期！";
            } else {
                $sql = "INSERT INTO products (name, price, description, expiry_date, stock_date) 
                        VALUES ('$name', '$price', '$description', '$expiry_date', '$stock_date')";

                if ($con->query($sql) === TRUE) {
                    // 新增成功，顯示選項讓用戶選擇是否繼續
                    echo "<script>
                            alert('商品新增成功！');
                            window.location.href = 'add_product.php?success=true';
                          </script>";
                    exit();  // 確保程式在跳轉後終止執行
                } else {
                    $error = "新增商品失敗：" . $con->error;
                }
            }
        } else {
            $error = "所有欄位必須填寫！";
        }

        $con->close();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>新增商品</title>
    <style>
        body {
            font-family: 'Microsoft JhengHei', Arial, sans-serif;
            background-color: #F5F5F5; /* 設定背景顏色 */
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

        label {
            font-weight: bold;
            display: block;
            text-align: left;
            margin: 10px 0 5px 5px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s ease;
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
            margin-bottom: 15px;
            font-size: 16px;
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
        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            <h1>商品新增成功！</h1>
            <p>您已成功新增商品。</p>
            <a href="add_product.php" class="btn">繼續新增商品</a><br><br>
            <a href="admin.php" class="btn-secondary">返回商品列表</a>
        <?php else: ?>
            <h1>新增商品</h1>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="get" action="add_product.php" onsubmit="return validateForm()">
                <label>商品名稱:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

                <label>價格:</label>
                <input type="number" id="price" step="0.01" name="price" value="<?php echo htmlspecialchars($price); ?>" min="0" required><br><br>

                <label>描述:</label>
                <textarea name="description" required><?php echo htmlspecialchars($description); ?></textarea><br><br>

                <label>進貨日期:</label>
                <input type="date" id="stock_date" name="stock_date" value="<?php echo htmlspecialchars($stock_date); ?>" required><br><br>

                <label>有效日期:</label>
                <input type="date" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($expiry_date); ?>" required><br><br>

                <button type="submit" class="btn">新增商品</button>
                <a href="admin.php" class="btn-secondary">取消</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
