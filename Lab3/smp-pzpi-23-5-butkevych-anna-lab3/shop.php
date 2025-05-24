<?php
session_start();
$products = [
    1 => ['name' => 'Fanta', 'price' => 1, 'image' => 'fanta.jpg'],
    2 => ['name' => 'Sprite', 'price' => 1, 'image' => 'sprite.jpg'],
    3 => ['name' => 'Nuts', 'price' => 1, 'image' => 'nuts.jpg'],
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantity'] as $id => $qty) {
        if (!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = 0;
        $_SESSION['cart'][$id] += (int)$qty;
    }
    header("Location: cart.php");
    exit();
}
?>

<?php include 'header.php'; ?>
<div class="container">
    <h2>Продукти</h2>
    <form method="post" class="product-form">
        <?php foreach ($products as $id => $item): ?>
            <div class="product-row">
                <img src="images/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="product-thumb">
                <span class="product-name"><?= htmlspecialchars($item['name']) ?></span>
                <input type="number" name="quantity[<?= $id ?>]" value="0" min="0" class="quantity-input">
                <span class="product-price">$<?= number_format($item['price'], 2) ?></span>
            </div>
        <?php endforeach; ?>
        <div class="form-actions">
            <button type="submit" class="btn">Send</button>
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>
