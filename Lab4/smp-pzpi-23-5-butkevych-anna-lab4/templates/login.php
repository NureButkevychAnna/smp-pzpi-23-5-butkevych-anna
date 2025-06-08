<div class="login-container">
    <h2>Вхід в систему</h2>
    
    <?php if (isset($_GET['required']) && $_GET['required'] === 'true'): ?>
        <p class="error-message">Для доступу до цієї сторінки необхідно увійти в систему.</p>
    <?php endif; ?>

    <?php if (isset($login_error)): ?>
        <p class="error-message"><?= $login_error ?></p>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <input type="hidden" name="action" value="login">
        <div class="row">
            <div class="six columns">
                <label for="username">Логін</label>
                <input class="u-full-width" type="text" id="username" name="username" placeholder="testuser" required>
            </div>
            <div class="six columns">
                <label for="password">Пароль</label>
                <input class="u-full-width" type="password" id="password" name="password" placeholder="password123" required>
            </div>
        </div>
        <button type="submit" class="button-primary">Увійти</button>
    </form>
</div>