<nav class="nav">
    <a href="index.php" class="nav__link">Главная</a>
    <a href="catalog.php" class="nav__link">Каталог</a>
    <a href="#services" class="nav__link">Услуги</a>
    <a href="#about" class="nav__link">О нас</a>
    <a href="cart.php" class="nav__link">Корзина</a>
    <a href="favorites.php" class="nav__link">Избранное</a>
    
    <?php
    // Проверяем, авторизован ли пользователь
    $user = check_user();

    // Если авторизован - показываем профиль и выход
    if ($user !== '') {
        echo '<a href="profile.php" class="nav__link">Профиль (<b>'.$user.'</b>)</a>';
        echo '<a href="includes/logout.php" class="nav__link nav__link--btn">Выход</a>';
    } else {
        // Если не авторизован - показываем ссылку на вход
        echo '<a href="login_page.php" class="nav__link nav__link--btn">Вход</a>';
    }
    ?>
</nav>