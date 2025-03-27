<?php
include("connection.php");

// 預設排序參數
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'name';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// 防止 SQL 注入
$allowed_columns = ['name', 'price', 'stock_date', 'expiry_date'];
$allowed_order = ['ASC', 'DESC'];
$allowed_search_columns = ['name', 'description', 'stock_date', 'expiry_date'];

if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'name';
}
if (!in_array($sort_order, $allowed_order)) {
    $sort_order = 'ASC';
}

// 查詢商品，預設按名稱升序排序
$sql = "SELECT * FROM products ORDER BY $sort_column $sort_order";

// 處理搜尋請求
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search']) && isset($_GET['search_column'])) {
    $search = $con->real_escape_string($_GET['search']);
    $search_column = in_array($_GET['search_column'], $allowed_search_columns) ? $_GET['search_column'] : 'name';

    $sql = "SELECT * FROM products WHERE $search_column LIKE '%$search%' ORDER BY $sort_column $sort_order";
}

$result = $con->query($sql);
$con->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>商品列表</title>
    <style>
        body {
            font-family: 'Microsoft JhengHei', Arial, sans-serif;
            background-color: #F5F5F5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .sort-form {
            text-align: center;
            margin-bottom: 20px;
        }

        .sort-form label {
            font-size: 16px;
            margin-right: 8px;
            font-weight: bold;
        }

        .sort-form select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .sort-form button {
            padding: 10px 18px;
            font-size: 16px;
            font-weight: bold;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        .sort-form button:hover {
            background: linear-gradient(135deg, #0056b3, #003d80);
            transform: scale(1.05);
        }

        .search-form select, .search-form input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-form button {
            padding: 10px 18px;
            font-size: 16px;
            font-weight: bold;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        .search-form button:hover {
            background: linear-gradient(135deg, #0056b3, #003d80);
            transform: scale(1.05);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions a {
            padding: 6px 12px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            margin-right: 5px;
        }
        .actions .update {
            background-color: #28a745;
        }
        .actions .delete {
            background-color: #dc3545;
        }
        .add-product {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #17a2b8;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .add-product:hover {
            background-color: #117a8b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>商品列表</h1>
        
        <!-- 搜尋表單 -->
        <form method="get" class="search-form">
            <select name="search_column">
                <option value="name" <?php echo (isset($_GET['search_column']) && $_GET['search_column'] === 'name') ? 'selected' : ''; ?>>名稱</option>
                <option value="price" <?php echo (isset($_GET['search_column']) && $_GET['search_column'] === 'price') ? 'selected' : ''; ?>>價格</option>
                <option value="description" <?php echo (isset($_GET['search_column']) && $_GET['search_column'] === 'description') ? 'selected' : ''; ?>>描述</option>
                <option value="stock_date" <?php echo (isset($_GET['search_column']) && $_GET['search_column'] === 'stock_date') ? 'selected' : ''; ?>>進貨日期</option>
                <option value="expiry_date" <?php echo (isset($_GET['search_column']) && $_GET['search_column'] === 'expiry_date') ? 'selected' : ''; ?>>有效日期</option>
            </select>
            <input type="text" name="search" placeholder="搜尋商品..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">搜尋</button>
        </form>

        <!-- 排序表單 -->
        <form method="get" class="sort-form">
            <label for="sort_column">排序依據：</label>
            <select name="sort_column">
                <option value="name" <?php echo ($sort_column === 'name') ? 'selected' : ''; ?>>名稱</option>
                <option value="price" <?php echo ($sort_column === 'price') ? 'selected' : ''; ?>>價格</option>
                <option value="stock_date" <?php echo ($sort_column === 'stock_date') ? 'selected' : ''; ?>>進貨日期</option>
                <option value="expiry_date" <?php echo ($sort_column === 'expiry_date') ? 'selected' : ''; ?>>有效日期</option>
            </select>

            <label for="sort_order">排序方式：</label>
            <select name="sort_order">
                <option value="ASC" <?php echo ($sort_order === 'ASC') ? 'selected' : ''; ?>>升序</option>
                <option value="DESC" <?php echo ($sort_order === 'DESC') ? 'selected' : ''; ?>>降序</option>
            </select>

            <button type="submit">排序</button>
        </form>


        <a href="add_product.php" class="add-product">新增商品</a>

        <table>
            <tr>
                <th>名稱</th>
                <th>價格</th>
                <th>描述</th>
                <th>進貨日期</th>
                <th>有效日期</th>
                <th>操作</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while($product = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($product['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['price']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['stock_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['expiry_date']) . "</td>";
                    echo "<td class='actions'>
                        <a href='update_product.php?id=" . urlencode($product['id']) . "' class='update'>更新</a>
                        <a href='delete_product.php?id=" . urlencode($product['id']) . "' class='delete' onclick='return confirm(\"確定要刪除嗎？\")'>刪除</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>找不到符合條件的商品</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
