<h2>Наші товари</h2>

<?php
$stmt = $pdo->query("SELECT * FROM products ORDER BY name");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<form action="index.php" method="POST">
    <input type="hidden" name="action" value="update_cart">
    
    <table class="u-full-width">
        <thead>
            <tr>
                <th>Назва товару</th>
                <th>Опис</th>
                <th>Ціна</th>
                <th>Кількість</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><?= number_format($product['price'], 2) ?> грн</td>
                    <td>
                        <input type="number" name="quantities[<?= $product['id'] ?>]" value="0" min="0" style="width: 80px;">
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" class="button-primary u-pull-right">Send</button>
</form>