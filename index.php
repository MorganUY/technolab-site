<?php 
// Подключаем функции (сессия, куки, работа с БД)
require_once('includes/functions.php'); 

// Сообщение об успешном заказе
$order_success = isset($_SESSION['order_success']);
if ($order_success) {
    unset($_SESSION['order_success']);
}

// Добавляем страницу в историю посещений
add_to_history();

// Запрос данных из таблицы main
$result = dbquery("SELECT * FROM main WHERE id = 1");
$data = dbfetcha($result);

// Если данные пустые - подставляем заглушки
if (empty($data['onas'])) {
    $onas = 'Информация о компании';
} else {
    $onas = $data['onas'];
}

if (empty($data['slider'])) {
    $slider = 'photo1.jpg';
} else {
    $slider = $data['slider'];
}

if (empty($data['services'])) {
    $services = 'Услуги';
} else {
    $services = $data['services'];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ТехноЛаб — Сервисный центр</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<?php if ($order_success): ?>
<script>alert('Заказ оформлен');</script>
<?php endif; ?>

    <header class="header">
        <div class="container header__container">
            <div class="logo-group">
                <?php include('top/logo.php'); ?>
            </div>
            
            <?php include('top/menu.php'); ?>
        </div>
    </header>

    <main>
        <section class="hero-slider">
            <div class="container slider-wrapper">
                <div class="slider-info">
                    <h1 class="slider-title">Сервисный центр <span class="highlight">"ТехноЛаб"</span></h1>
                    <p id="sliderText" class="slider-text"><?php echo strip_tags($onas); ?></p>
                    
                    <div class="slider-controls">
                        <button class="slider-btn slider-btn--prev" aria-label="Предыдущий">←</button>
                        <span class="dot active" data-index="0"></span>
                        <span class="dot" data-index="1"></span>
                        <span class="dot" data-index="2"></span>
                        <button class="slider-btn slider-btn--next" aria-label="Следующий">→</button>
                    </div>
                </div>
                
                <div class="slider-visual">
                    <img id="mainImage" src="images/<?php echo $slider; ?>" alt="Slide" class="main-slide-img" onerror="this.src='images/photo1.jpg'">
                </div>
            </div>
        </section>

        <section id="services" class="services">
            <div class="container">
                <h2 class="section-title">Услуги и цены</h2>
                <div class="services-grid">
                    <div class="service-item">
                        <div class="service-icon">💻</div>
                        <p class="service-label"><?php echo $services; ?></p>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">📱</div>
                        <p class="service-label">Ремонт телефонов и планшетов</p>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">📡</div>
                        <p class="service-label">Спутниковые и приставки</p>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">📺</div>
                        <p class="service-label">Ремонт телевизоров и мониторов</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="about">
            <div class="container">
                <h2 class="section-title white-title">О нашем сервисе</h2>
                <div class="about-content">
                    <div class="about-text">
                        <p><?php echo $onas; ?></p>
                    </div>
                    <div class="about-stats">
                        <div class="stat-card"><strong>5 лет</strong> опыта</div>
                        <div class="stat-card"><strong>100%</strong> гарантия</div>
                        <div class="stat-card"><strong>Сжатые</strong> сроки</div>
                        <div class="stat-card"><strong>Честные</strong> цены</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        const phpSliderData = [
            {
                title: 'Сервисный центр <span class="highlight">"ТехноЛаб"</span>',
                text: '<?php echo str_replace(array("\r", "\n"), '', strip_tags($onas)); ?>',
                img: 'images/<?php echo $slider; ?>'
            },
            {
                title: 'Сборка <span class="highlight">и апгрейд ПК</span>',
                text: 'Индивидуальный подбор комплектующих под ваш бюджет и задачи.',
                img: 'images/2.jpg' 
            },
            {
                title: 'Настройка <span class="highlight">ПО и сетей</span>',
                text: 'Установка операционных систем, драйверов и защита от вирусов.',
                img: 'images/photo2.jpg'
            }
        ];
    </script>

    <script src="script.js"></script>
</body>
</html>