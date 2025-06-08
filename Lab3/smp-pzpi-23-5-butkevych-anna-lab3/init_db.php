<?php
$pdo = new PDO('sqlite:shop.db');

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Connected to database successfully.\n";

$pdo->exec("
    CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        price REAL NOT NULL,
        image TEXT
    )
");

echo "Table 'products' created successfully.\n";

$pdo->exec("
    CREATE TABLE IF NOT EXISTS cart (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id)
    )
");

echo "Table 'cart' created successfully.\n";

$stmt = $pdo->query("SELECT COUNT(*) FROM products");
if ($stmt->fetchColumn() == 0) {
    $products = [
        ['Fanta', 'Газований апельсиновий напій', 25.50, 'fanta.jpg'],
        ['Sprite', 'Газований лимонний напій', 24.00, 'sprite.jpg'],
        ['Nuts', 'Шоколадний батончик з горіхами', 35.00, 'nuts.jpg'],
        ['Water', 'Мінеральна вода без газу', 15.00, 'water.jpg']
    ];

    $insert = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    foreach ($products as $product) {
        $insert->execute($product);
    }
    echo "Products seeded successfully.\n";
} else {
    echo "Products table is not empty, skipping seeding.\n";
}