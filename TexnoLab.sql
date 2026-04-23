-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Апр 23 2026 г., 11:04
-- Версия сервера: 8.4.6
-- Версия PHP: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `TexnoLab`
--

-- --------------------------------------------------------

--
-- Структура таблицы `callback_requests`
--

CREATE TABLE `callback_requests` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `status` varchar(50) DEFAULT 'Новая',
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `callback_requests`
--

INSERT INTO `callback_requests` (`id`, `user_id`, `customer_name`, `phone_number`, `status`, `comment`, `created_at`) VALUES
(4, 6, 'test1', '+123', 'в работе', 'надо починить ноутбук привезу к 12 часам', '2026-04-17 11:00:38'),
(5, 7, 'test', '+1234', 'обработан', '19:00', '2026-04-17 11:01:33'),
(6, 8, 'test3', '+123 45', 'Новая', 'Премичание', '2026-04-17 11:06:03'),
(7, 26, '134', '555', 'Новая', '13513', '2026-04-17 15:25:32');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text,
  `parent_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `parent_id`) VALUES
(1, 'Комплектующие', NULL, NULL),
(2, 'Периферия', NULL, NULL),
(3, 'Готовые сборки', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `main`
--

CREATE TABLE `main` (
  `id` int NOT NULL,
  `onas` text NOT NULL,
  `slider` varchar(255) DEFAULT NULL,
  `services` text,
  `images` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `main`
--

INSERT INTO `main` (`id`, `onas`, `slider`, `services`, `images`) VALUES
(1, '<h1>ТехноЛаб</h1><p>Сервисный центр «ТехноЛаб» осуществляет ремонт цифровой техники в кратчайшие сроки. Мы используем профессиональное оборудование и оригинальные запчасти.</p>\n                        <p>Наши мастера постоянно повышают квалификацию, чтобы справляться с самыми сложными поломками современных гаджетов.</p>', 'slider1.jpg', 'Ремонт ПК, Настройка ПО, Сборка компьютеров', 'placeholder.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_text` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `short_text`, `image`) VALUES
(1, 'NEW day', 'Актуальность темы исследования обусловлена постоянно растущими требованиями к качеству взаимодействия пользователей с онлайн-сервисами. В условиях стремительного развития цифровой экономики удобный.\r\n', 'лого2.png'),
(2, 'new 2', 'Разработка удобного и функционального веб-приложения для электронной коммерции на базе маркетплейса Ozon, расширение клиентской аудитории, повышение лояльности покупателей и автоматизация процессов управления онлайн-торговлей.', 'photo1.jpg'),
(3, 'new 3 ', 'Основными задачами являются создание интуитивно понятного интерфейса для пользователей, реализация системы управления товарами и заказами для продавцов, обеспечение безопасности.\r\n', 'photo2.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `status` varchar(20) DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `comment` text,
  `payment` varchar(20) DEFAULT 'cash',
  `delivery` varchar(20) DEFAULT 'pickup',
  `total_price` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `created_at`, `name`, `phone`, `email`, `address`, `comment`, `payment`, `delivery`, `total_price`) VALUES
(9, NULL, 'Новый', '2026-04-17 22:48:41', 'name', '123', 'test6@gmail.com', NULL, NULL, 'cash', 'pickup', 0),
(10, NULL, 'Отменен', '2026-04-17 22:51:09', 'name', '1245', 'test6@gmail.com', NULL, '1', 'cash', 'pickup', 0),
(11, NULL, 'Отменен', '2026-04-17 22:52:18', 'test6', '+123 45679', 'test6@gmail.com', NULL, '0', 'cash', 'pickup', 0),
(12, NULL, 'Подтвержден', '2026-04-17 22:53:44', 'test6', '+123 45679', 'test6@gmail.com', NULL, NULL, 'cash', 'pickup', 70000),
(13, NULL, 'Подтвержден', '2026-04-17 22:54:05', 'test', '135', 'test6@gmail.com', NULL, NULL, 'cash', 'pickup', 35000),
(14, NULL, 'Новый', '2026-04-18 07:33:54', 'test7', '+7 920 832 45 34', 'test8@gmail.com', NULL, NULL, 'cash', 'pickup', 85000),
(15, 11, 'Новый', '2026-04-18 08:10:12', 'pop', '19139', 'test9@gmail.com', NULL, NULL, 'cash', 'pickup', 64500),
(16, 11, 'Подтвержден', '2026-04-18 08:38:20', 'test6', '1421', 'test6@gmail.com', NULL, NULL, 'cash', 'pickup', 120000),
(17, 11, 'Новый', '2026-04-22 20:56:03', 'test6', '124124', 'test6@gmail.com', NULL, NULL, 'cash', 'pickup', 12500);

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED DEFAULT NULL,
  `product_id` int UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 12, 1, 2, 35000),
(2, 13, 1, 1, 35000),
(3, 14, 1, 2, 35000),
(4, 14, 2, 1, 15000),
(5, 15, 2, 4, 15000),
(6, 15, 3, 1, 4500),
(7, 16, 1, 3, 35000),
(8, 16, 2, 1, 15000),
(9, 17, 4, 5, 2500);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `category_id` int UNSIGNED DEFAULT NULL,
  `stock` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `stock`, `created_at`, `image`) VALUES
(1, 'Видеокарта RTX 4060', 'Полное техническое описание товара.\n\r\n1. Частота графического процессора: 1830 МГц\n\r\n2. Объем видеопамяти: 8 ГБ GDDR6\n\r\n3. Техпроцесс: 8 нм', 35000.00, 1, 5, '2026-04-17 19:14:02', '4060.jpg'),
(2, 'Процессор Core i5-12400F', '6 ядер, 12 потоков, идеален для сборок', 15000.00, 1, 11, '2026-04-17 19:14:02', 'Процессор Core i5-12400F.jpg'),
(3, 'Механическая клавиатура', 'RGB подсветка, синие свичи', 4500.00, 2, 15, '2026-04-17 19:14:02', 'Механическая клавиатура.jpg'),
(4, 'Игровая мышь', 'Оптический сенсор 16000 DPI', 2500.00, 2, 20, '2026-04-17 19:14:02', 'Игровая мышь.jpg'),
(6, 'мышь офисная', 'просто мышь', 100.00, 2, 1999, '2026-04-17 23:04:48', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70'),
(9, 'test1', '202cb962ac59075b964b07152d234b70'),
(10, 'test2', '9b04d152845ec0a378394003c96da594'),
(11, 'tester', '5eac43aceba42c8757b54003a58277b5');

-- --------------------------------------------------------

--
-- Структура таблицы `users2`
--

CREATE TABLE `users2` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `role` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users2`
--

INSERT INTO `users2` (`id`, `username`, `password`, `phone`, `address`, `email`, `zip`, `created_at`, `reset_token`, `role`) VALUES
(4, 'Admin', '$2y$12$/wxjGDDJRAGT6NUHjcHTKeBhjOyyI4QAs2sVYyqnEOeZrqUWeebiq', '', '', NULL, NULL, '2026-04-17 10:31:30', NULL, 1),
(6, 'test1', '$2y$12$kU48CTRZWR0TVDmuX2SSE.0T4T/8dtyXzMUVaR.Y6oENcH8TLr49K', '+123', 'roket', NULL, NULL, '2026-04-17 11:00:00', NULL, 0),
(7, 'test2', '$2y$12$mXBd6LQsFuDQp5q9hWsXru.B2/355dMvdgQzyQqKLaWTQRbs.9LVu', '+1234', 'Avdeivka', NULL, NULL, '2026-04-17 11:01:05', NULL, 0),
(8, 'test3', '$2y$12$STEm1NxrrqsfWiBuruQNjeOCBE3HulAxyO9WVErTjVAuSCfrmb5R2', '+123 45', 'Comsomol', NULL, NULL, '2026-04-17 11:02:30', NULL, 0),
(9, 'test4', '$2y$12$iaILSIe31FYvkYD2OEGc9eqgVoYNp6amu.bgew8.Etbdf1Vo8kn4e', '+123 456', 'cochka', 'test4@gmail.com', '124145', '2026-04-17 11:39:41', NULL, 0),
(10, 'test5', '$2y$12$G8ToWiSJHCmeN/y4xDjVLesZ2CirnNhlvSwRNdfZ/EsH2/CnMsx4u', '+123 456 1', 'adres', 'test5@gmail.com', '124215', '2026-04-17 11:42:00', NULL, 0),
(11, 'test6', '$2y$12$TV3wzvIAIP4eHZrZgGFeWuBxD5gefHrL.hpbNVhvEb3gULpaJ3wsW', '+123 45679', 'Dom', 'test6@gmail.com', '125678', '2026-04-17 11:51:12', NULL, 0),
(20, 'abd', '$2y$12$cJd0BYA6Vrkaw2Y85KNRMOu8fbWgO.g54RmHswd.OLWVQA1xniVHG', '', '', NULL, NULL, '2026-04-17 12:04:52', NULL, 1),
(23, 'admin2', '$2y$12$lJR3cRP74VwWz3uqr2MBAuvwMyE23ExI78bmlhjnTR39sdJBY7bMC', '', '', NULL, NULL, '2026-04-17 12:06:59', NULL, 1),
(24, 'test8', '$2y$12$rVMNtvLE5DlTCq7mzBObDeaFOwH.YL49D2AjzEjI4.EVQev.QMRWK', '1234144', 'Popcorn', 'test8@gmail.com', '123456', '2026-04-17 12:32:19', NULL, 0),
(25, 'test7', '$2y$12$CumXEPLscdp0.xB6t0HCyesgy895x15vWQgFZ3uTlrDBiR/dV.L9G', '+2 249 02-32', 'QWErty', 'test7@gmail.com', '678912', '2026-04-17 14:27:01', NULL, 0),
(26, 'test9', '$2y$12$O3Gk8pJq0BPI/qLiU39fvO9ESFG/kTQ1N.10vYX5G20q8ku5jQXOa', '-241 152', 'orel', 'test9@gmail.com', '123456', '2026-04-17 15:24:33', NULL, 0),
(27, 'Admim', '$2y$12$bbfrrT2nw4MWE3FIjtVx8enFKn5IRzy0Qu/x8LdNdKr6dEYjzYvYC', '', '', NULL, NULL, '2026-04-22 20:50:14', NULL, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `callback_requests`
--
ALTER TABLE `callback_requests`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`);

--
-- Индексы таблицы `main`
--
ALTER TABLE `main`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_user_new` (`user_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users2`
--
ALTER TABLE `users2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `callback_requests`
--
ALTER TABLE `callback_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `main`
--
ALTER TABLE `main`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `users2`
--
ALTER TABLE `users2`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_user_new` FOREIGN KEY (`user_id`) REFERENCES `users2` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
