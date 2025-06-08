<h2>Ваш кошик</h2>

<?php
$stmt = $pdo->query("
    SELECT p.id, p.name, p.price, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.quantity > 0
");
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPrice = 0;
?>

<?php if (empty($cartItems)): ?>
    <p>Ваш кошик порожній.</p>
    <?php if (isset($_GET['status']) && $_GET['status'] === 'paid'): ?>
        <p class="success-message"><strong>Дякуємо! Ваше замовлення успішно оплачено.</strong></p>
    <?php endif; ?>
    <a href="index.php?page=products" class="button">Перейти до покупок</a>
<?php else: ?>
    <table class="u-full-width">
        <thead>
            <tr>
                <th>Назва товару</th>
                <th>Ціна за од.</th>
                <th>Кількість</th>
                <th>Сума</th>
                <th>Дія</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <?php $itemTotal = $item['price'] * $item['quantity']; ?>
                <?php $totalPrice += $itemTotal; ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= number_format($item['price'], 2) ?> грн</td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($itemTotal, 2) ?> грн</td>
                    <td>
                        <form action="index.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="action" value="remove_from_cart">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="button-danger">Вилучити</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">Загальна вартість:</td>
                <td style="font-weight: bold;"><?= number_format($totalPrice, 2) ?> грн</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="cart-actions">
        <form action="index.php" method="POST" class="cancel-form">
             <input type="hidden" name="action" value="cancel_order">
             <button type="submit" class="button">Cancel</button>
        </form>
        <form action="index.php" method="POST" class="pay-form">
             <input type="hidden" name="action" value="pay_order">
             <button type="submit" class="button-primary">Pay</button>
        </form>
    </div>
<?php endif; ?>