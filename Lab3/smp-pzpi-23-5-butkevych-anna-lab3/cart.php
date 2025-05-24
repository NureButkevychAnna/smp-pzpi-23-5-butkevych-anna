<?php
session_start();
$products = [
    1 => ['name' => 'Fanta', 'price' => 1],
    2 => ['name' => 'Sprite', 'price' => 1],
    3 => ['name' => 'Nuts', 'price' => 1],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'cancel':
                unset($_SESSION['cart']);
                header('Location: cart.php');
                exit;
            case 'pay':
                unset($_SESSION['cart']);
                $paid = true;
                break;
            case 'remove':
                $removeId = intval($_POST['product_id']);
                unset($_SESSION['cart'][$removeId]);
                break;
        }
    }
}
?>

<?php include 'header.php'; ?>
<div class="container">
    <h2>Кошик</h2>

    <?php if (!empty($paid)): ?>
        <div class="message message-success">Дякуємо за покупку! Ваше замовлення прийнято.</div>
    <?php elseif (empty($_SESSION['cart'])): ?>
        <div class="message message-warning">Кошик порожній. <a href="products.php">Перейти до покупок</a></div>
    <?php else: ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr><th>ID</th><th>Назва</th><th>Ціна</th><th>Кількість</th><th>Сума</th><th>Дія</th></tr>
                </thead>
                <tbody>
                <?php $total = 0; foreach ($_SESSION['cart'] as $id => $qty): ?>
                    <?php if (isset($products[$id])): ?>
                        <?php
                            $item = $products[$id];
                            $sum = $item['price'] * $qty;
                            $total += $sum;
                        ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $qty ?></td>
                            <td>$<?= number_format($sum, 2) ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= $id ?>">
                                    <button type="submit" name="action" value="remove" class="btn btn-danger" title="Видалити товар">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4"><strong>Total</strong></td>
                    <td><strong>$<?= number_format($total, 2) ?></strong></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>

        <form method="POST" style="margin-top: 1rem; display: flex; gap: 1rem;">
            <button type="submit" name="action" value="cancel" class="btn btn-danger">cancel</button>
            <button type="submit" name="action" value="pay" class="btn btn-secondary">pay</button>
        </form>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
