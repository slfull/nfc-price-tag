export default function handler(req, res) {
    const products = {
        "ITEM1": {
          "name": "無線耳機",
          "price": "$25",
          "note": "音質極佳，舒適佩戴"
        },
        "ITEM2": {
          "name": "滑鼠垫",
          "price": "$10",
          "note": "防滑設計，適合各種滑鼠"
        },
        "ITEM3": {
          "name": "鍵盤",
          "price": "$45",
          "note": "機械式鍵盤，回饋良好"
        },
        "ITEM4": {
          "name": "USB-C 充電線",
          "price": "$15",
          "note": "高速傳輸，長度1.5米"
        },
        "ITEM5": {
          "name": "手機支架",
          "price": "$20",
          "note": "可調整角度，適合各型手機"
        }
      };

    const { id } = req.query;  // 讀取網址參數
    if (products[id]) {
        res.status(200).json(products[id]);
    } else {
        res.status(404).json({ error: "商品未找到" });
    }
}