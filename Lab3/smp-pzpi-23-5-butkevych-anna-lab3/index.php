<?php
require_once 'database.php';

$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_cart') {
    $quantities = $_POST['quantities'] ?? [];
    
    $pdo->exec("DELETE FROM cart");
    
    $insertStmt = $pdo->prepare("INSERT INTO cart (product_id, quantity) VALUES (?, ?)");

    foreach ($quantities as $productId => $quantity) {
        $productId = (int)$productId;
        $quantity = (int)$quantity;
        if ($productId > 0 && $quantity > 0) {
            $insertStmt->execute([$productId, $quantity]);
        }
    }
    
    header('Location: index.php?page=cart');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'remove_from_cart') {
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    if ($productId) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE product_id = ?");
        $stmt->execute([$productId]);
    }
    header('Location: index.php?page=cart');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'cancel_order') {
    $pdo->exec("DELETE FROM cart");
    header('Location: index.php?page=products');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'pay_order') {
    $pdo->exec("DELETE FROM cart");
    header('Location: index.php?page=cart&status=paid');
    exit;
}


$page = $_GET['page'] ?? 'home'; 

require_once 'templates/header.php';

switch ($page) {
    case 'products':
        require_once 'templates/products.php';
        break;
    case 'cart':
        require_once 'templates/cart.php';
        break;
    case 'home':
    default:
        require_once 'templates/home.php';
        break;
}

require_once 'templates/footer.php';