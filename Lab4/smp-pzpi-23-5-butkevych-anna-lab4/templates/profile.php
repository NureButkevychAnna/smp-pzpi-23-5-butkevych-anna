<?php
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<h2>Профіль користувача: <?= htmlspecialchars($user['username']) ?></h2>

<?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
    <p class="success-message">Профіль успішно оновлено!</p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="error-message">
        <p><strong>Будь ласка, виправте наступні помилки:</strong></p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="index.php?page=profile" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="update_profile">
    <div class="row">
        <div class="four columns profile-avatar">
            <label>Ваш аватар</label>
            <img src="<?= !empty($user['avatar']) ? 'public/uploads/' . htmlspecialchars($user['avatar']) : 'https://via.placeholder.com/150' ?>" alt="User Avatar" class="avatar-image">
            <input type="file" name="avatar" id="avatar">
        </div>
        <div class="eight columns">
            <label for="full_name">Повне ім'я</label>
            <input class="u-full-width" type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? $user['full_name'] ?? '') ?>">

            <label for="birth_date">Дата народження</label>
            <input class="u-full-width" type="date" name="birth_date" id="birth_date" value="<?= htmlspecialchars($_POST['birth_date'] ?? $user['birth_date'] ?? '') ?>">
            
            <label for="description">Коротка інформація</label>
            <textarea class="u-full-width" name="description" id="description"><?= htmlspecialchars($_POST['description'] ?? $user['description'] ?? '') ?></textarea>
        </div>
    </div>
    <button type="submit" class="button-primary">Зберегти зміни</button>
</form>