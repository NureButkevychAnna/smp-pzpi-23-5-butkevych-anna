<?php
session_start();

require_once 'database.php';

$action = $_POST['action'] ?? '';
$is_logged_in = isset($_SESSION['user_id']);

if ($action === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php?page=profile');
        exit;
    } else {
        $login_error = "Неправильний логін або пароль.";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.php?page=home');
}

$action = $_POST['action'] ?? '';
$is_logged_in = isset($_SESSION['user_id']);

if ($action === 'update_profile' && $is_logged_in) {
    $errors = [];
    $userId = $_SESSION['user_id'];
    
    $full_name = trim($_POST['full_name'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($full_name) || strlen($full_name) <= 1) {
        $errors[] = "Повне ім'я є обов'язковим і має містити більше одного символу.";
    }
    if (empty($birth_date)) {
        $errors[] = "Дата народження є обов'язковою.";
    } else {
        $today = new DateTime();
        $birthDateObj = new DateTime($birth_date);
        $age = $today->diff($birthDateObj)->y;
        if ($age < 16) {
            $errors[] = "Реєстрація дозволена лише з 16 років.";
        }
    }
    if (empty($description)) {
        $errors[] = "Коротка інформація є обов'язковою.";
    }

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2 MB

        if (!in_array($_FILES['avatar']['type'], $allowedTypes)) {
            $errors[] = "Неприпустимий тип файлу. Дозволено тільки JPG, PNG, GIF.";
        }
        if ($_FILES['avatar']['size'] > $maxSize) {
            $errors[] = "Файл занадто великий. Максимальний розмір - 2 MB.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, birth_date = ?, description = ? WHERE id = ?");
        $stmt->execute([$full_name, $birth_date, $description, $userId]);
        
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
            $fileName = uniqid() . '-' . basename($_FILES['avatar']['name']);
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
                $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                $stmt->execute([$fileName, $userId]);
            }
        }
        header('Location: index.php?page=profile&status=success');
        exit;
    }
}

if ($action === 'update_cart' && $is_logged_in) {
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

if ($action === 'remove_from_cart' && $is_logged_in) {
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    if ($productId) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE product_id = ?");
        $stmt->execute([$productId]);
    }
    header('Location: index.php?page=cart');
    exit;
}

if ($action === 'cancel_order' && $is_logged_in) {
    $pdo->exec("DELETE FROM cart");
    header('Location: index.php?page=products');
    exit;
}

if ($action === 'pay_order' && $is_logged_in) {
    $pdo->exec("DELETE FROM cart");
    header('Location: index.php?page=cart&status=paid');
    exit;
}

$page = $_GET['page'] ?? 'home';
$is_logged_in = isset($_SESSION['user_id']);
$protected_pages = ['cart', 'profile'];
$available_pages = ['home', 'products', 'cart', 'login', 'profile']; 
if (in_array($page, $protected_pages) && !$is_logged_in) {
    header('Location: index.php?page=login&required=true');
    exit;
}

require_once 'templates/header.php';

if (!in_array($page, $available_pages)) {
    http_response_code(404);
    require_once 'templates/page404.php';
} else {
    switch ($page) {
        case 'products': require_once 'templates/products.php'; break;
        case 'cart': require_once 'templates/cart.php'; break;
        case 'login': require_once 'templates/login.php'; break;
        case 'profile': require_once 'templates/profile.php'; break;
        case 'home':
        default: require_once 'templates/home.php'; break;
    }
}

require_once 'templates/footer.php';