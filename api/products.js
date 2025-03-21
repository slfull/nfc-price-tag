export default function handler(req, res) {
    const products = {
        "ITEM12345": { id: "ITEM12345", name: "商品 A", price: 100, note: "限時優惠" },
        "ITEM67890": { id: "ITEM67890", name: "商品 B", price: 250, note: "新品上市" },
        "ITEM54321": { id: "ITEM54321", name: "商品 C", price: 80, note: "折扣中" }
    };

    const { id } = req.query;  // 讀取網址參數
    if (products[id]) {
        res.status(200).json(products[id]);
    } else {
        res.status(404).json({ error: "商品未找到" });
    }
}