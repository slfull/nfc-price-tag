const express = require('express');
const cors = require('cors');
const fs = require('fs');
const path = require('path');

const app = express();
app.use(cors());

// 讀取 JSON 商品資料
const productsFile = path.join(__dirname, 'products.json');
let productData = {};

// 初始化時讀取 JSON 檔案
fs.readFile(productsFile, 'utf8', (err, data) => {
    if (!err) {
        productData = JSON.parse(data);
        console.log("商品資料載入成功！", productData);
    } else {
        console.error("讀取商品資料失敗：", err);
    }
});

// API: 查詢商品資訊
app.get('/product/:id', (req, res) => {
    const productId = req.params.id;
    const product = productData[productId];

    if (!product) {
        return res.send(`
            <!DOCTYPE html>
            <html lang="zh">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>商品查詢</title>
            </head>
            <body>
                <h2>商品查詢</h2>
                <p>商品 ID: ${productId}</p>
                <p style="color: red;">找不到該商品</p>
            </body>
            </html>
        `);
    }

    res.send(`
        <!DOCTYPE html>
        <html lang="zh">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>商品資訊</title>
        </head>
        <body>
            <h2>商品資訊</h2>
            <p><strong>商品 ID:</strong> ${productId}</p>
            <p><strong>名稱:</strong> ${product.name}</p>
            <p><strong>價格:</strong> ${product.price}</p>
            <p><strong>備註:</strong> ${product.note}</p>
        </body>
        </html>
    `);
});
