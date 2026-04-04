-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost:3306
-- 生成日期： 2025-01-11 09:26:29
-- 服务器版本： 10.4.28-MariaDB
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `3cdn2214`
--

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `ordernumber` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `totalprice` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `orders`
--

INSERT INTO `orders` (`id`, `ordernumber`, `name`, `user_id`, `totalprice`, `created_at`, `phone`, `address`) VALUES
(5, 'cAK1wb7v', 'linxuanyi', NULL, 64100.00, '2025-01-05 15:40:14', '2345', 'dsarb'),
(6, 'A5wCVO6r', 'lxy', NULL, 87900.00, '2025-01-05 16:08:57', '123456789', '秦野市１１−１１'),
(8, 'lNZ9bYVU', '3cdn2214', NULL, 42200.00, '2025-01-05 16:17:06', '123456', 'abc'),
(9, 'VeTkRfWP', '3cdn2214', NULL, 30500.00, '2025-01-05 16:29:09', '123456', 'abc'),
(10, 'LgKmcisy', '3cdn1113', NULL, 9700.00, '2025-01-06 10:53:03', '123456', 'xxx'),
(11, 'ms3Afdrw', 'lin', NULL, 22800.00, '2025-01-10 12:30:17', '2345', 'dsarb'),
(12, 'sSyda8Jz', 'yu', NULL, 36800.00, '2025-01-10 13:47:08', '89', '89'),
(13, 'G75h3qHD', 'lin', NULL, 5800.00, '2025-01-11 16:58:10', '2345', 'dsarb'),
(14, 'ReySXbZq', 'ui', NULL, 17400.00, '2025-01-11 16:58:47', '89', '89');

-- --------------------------------------------------------

--
-- 表的结构 `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `ordernumber` varchar(50) NOT NULL,
  `productname` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `totalprice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `order_items`
--

INSERT INTO `order_items` (`id`, `ordernumber`, `productname`, `price`, `quantity`, `totalprice`) VALUES
(1, 'cAK1wb7v', '3.11', 9700.00, 2, 19400.00),
(2, 'cAK1wb7v', '6.14', 9700.00, 1, 9700.00),
(3, 'cAK1wb7v', '9.14', 7000.00, 5, 35000.00),
(4, 'A5wCVO6r', '3.14', 6900.00, 3, 20700.00),
(5, 'A5wCVO6r', '6.17', 7900.00, 3, 23700.00),
(6, 'A5wCVO6r', '12.6', 8500.00, 1, 8500.00),
(7, 'A5wCVO6r', '9.4', 7000.00, 5, 35000.00),
(10, 'lNZ9bYVU', '6.2', 7600.00, 3, 22800.00),
(11, 'lNZ9bYVU', '6.8', 9700.00, 2, 19400.00),
(12, 'VeTkRfWP', '3.2', 5700.00, 3, 17100.00),
(13, 'VeTkRfWP', '6.12', 6700.00, 2, 13400.00),
(14, 'LgKmcisy', '3.11', 9700.00, 1, 9700.00),
(15, 'ms3Afdrw', '3.2', 5700.00, 4, 22800.00),
(16, 'sSyda8Jz', '9.8', 7900.00, 2, 15800.00),
(17, 'sSyda8Jz', '9.14', 7000.00, 3, 21000.00),
(18, 'G75h3qHD', '3.3', 5800.00, 1, 5800.00),
(19, 'ReySXbZq', '3.3', 5800.00, 3, 17400.00);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `phone`, `address`) VALUES
(3, '3cdn2214', 'a123', '123456', 'abc'),
(4, 'lin', '567', '2345', 'dsarb'),
(5, 'bvc', 'qwe', '123', '456'),
(6, 'sdjlasd', '56789', '3456342', 'dsfntgtgh'),
(7, 'lxy', 'a123', '123456789', '秦野市１１−１１'),
(8, '3cdn1113', 'a123', '123456', 'xxx'),
(9, 'yu', 'nu', '89', '89'),
(10, 'ui', '89', '89', '89');

--
-- 转储表的索引
--

--
-- 表的索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ordernumber` (`ordernumber`);

--
-- 表的索引 `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordernumber` (`ordernumber`);

--
-- 表的索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 使用表AUTO_INCREMENT `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 限制导出的表
--

--
-- 限制表 `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`ordernumber`) REFERENCES `orders` (`ordernumber`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
