<?php
$pdo = new PDO('sqlite:shop.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("
    CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL,
        description TEXT, price REAL NOT NULL, image TEXT
    )
");

$pdo->exec("CREATE TABLE IF NOT EXISTS cart (
    id INTEGER PRIMARY KEY AUTOINCREMENT, product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL, FOREIGN KEY (product_id) REFERENCES products(id)
)");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        full_name TEXT,
        birth_date TEXT,
        description TEXT,
        avatar TEXT
    )
");

echo "Tables checked/created successfully.\n";

$stmt = $pdo->query("SELECT COUNT(*) FROM users");
if ($stmt->fetchColumn() == 0) {
    $username = 'testuser';
    $password_hash = password_hash('password123', PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)")
        ->execute([$username, $password_hash]);
    echo "Test user 'testuser' with password 'password123' created.\n";
} else {
    echo "Users table is not empty, skipping user seeding.\n";
}