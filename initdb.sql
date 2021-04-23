-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生時間： 2021 年 04 月 22 日 08:15
-- 伺服器版本: 5.7.33-0ubuntu0.18.04.1
-- PHP 版本： 7.3.27-9+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `shinmoon`
--

-- --------------------------------------------------------

--
-- 資料表結構 `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `account` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone_number` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oauth_type` int(11) NOT NULL DEFAULT '0',
  `oauth_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `reset_password` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_order_id` int(11) DEFAULT NULL,
  `activate` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `member`
--

INSERT INTO `member` (`id`, `account`, `password`, `email`, `name`, `telephone_number`, `oauth_type`, `oauth_id`, `reset_password`, `cart_order_id`, `activate`) VALUES
(1, 'testAccount', '$2y$12$IA4pMqNVt6n3K5sjsvJ0AuespHP8govogWvWAfAkm.OVUN6DKQZ26', 'testMail@mail.com', NULL, NULL, 0, '', NULL, NULL, b'0'),
(2, 'testAccount2', '$2y$12$Qt6pjKnff8RobicVKJQ6a.0p1Uz3gUFTwUn8DaLDwBWwCTO8fgLdK', 'testMail2@mail.com', NULL, NULL, 0, '', NULL, NULL, b'0'),
(3, 'testAccount3', '$2y$12$Qt6pjKnff8RobicVKJQ6a.0p1Uz3gUFTwUn8DaLDwBWwCTO8fgLdK', 'testMail3@mail.com', NULL, NULL, 0, '', NULL, NULL, b'0'),
(4, '', NULL, 'testFacebookOauthMail@mail.com', NULL, NULL, 1, 'facebook_oauth_id_1', NULL, NULL, b'0'),
(5, '', NULL, 'testGoogleOauthMail@mail.com', NULL, NULL, 2, 'google_oauth_id_1', NULL, NULL, b'0');

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `has_commit` bit(1) NOT NULL DEFAULT b'0',
  `invoice_type` int(11) NOT NULL DEFAULT '1',
  `svg_id` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `total` int(11) NOT NULL DEFAULT '0',
  `shipping_fee` int(11) NOT NULL DEFAULT '0',
  `recipient_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `recipient_telephone_number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `member_carrier` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `order_time` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `subtotal` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `main_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inventory` int(11) NOT NULL DEFAULT '0',
  `available` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `product`
--

INSERT INTO `product` (`id`, `product_type_id`, `product_name`, `price`, `main_image`, `inventory`, `available`) VALUES
(1, 1, '羅技 G102 炫彩遊戲滑鼠黑色', 599, 't1-1.jpg', 100, b'1'),
(2, 1, '羅技 G300s 電競遊戲滑鼠', 459, 't1-2.jpg', 100, b'1'),
(3, 1, '羅技 G304 無線電競滑鼠', 1090, 't1-3.jpg', 100, b'1'),
(4, 1, '羅技 G102 炫彩遊戲滑鼠白色', 590, 't1-4.jpg', 100, b'1'),
(5, 1, 'irocks M35W 光磁微動 電競滑鼠', 599, 't1-5.jpg', 100, b'1'),
(6, 1, 'irocks M36Pro 光磁微動 電競滑鼠', 999, 't1-6.jpg', 100, b'1'),
(7, 1, 'E-books M25隱形俠電競1600CPI滑鼠', 199, 't1-7.jpg', 100, b'1'),
(8, 1, 'E-books M24 電競六鍵式2400CPI光學滑鼠', 399, 't1-8.jpg', 100, b'1'),
(9, 2, 'irocks K69M 白色背光 超薄金屬 機械式鍵盤-茶軸', 2299, 't2-1.jpg', 100, b'1'),
(10, 2, 'Esense G8500跨界真機械鍵盤-青軸(13-EGK850)黑色', 799, 't2-2.jpg', 100, b'1'),
(11, 2, '羅技 G413 機械式背光遊戲鍵盤黑', 2490, 't2-3.jpg', 100, b'1'),
(12, 2, 'FOXXRAY 鏡甲電競鍵盤滑鼠組合包(FXR-CKM-10)', 631, 't2-4.jpg', 100, b'1'),
(13, 2, '雷蛇Razer Ornata V2 雨林狼蛛V2 機械式RGB鍵盤', 3290, 't2-5.jpg', 100, b'1'),
(14, 2, 'irocks K65MS 紅色背光 機械式鍵盤-Cherry茶軸', 2290, 't2-6.jpg', 100, b'1'),
(15, 2, 'irocks K68MN 無背光 機械式鍵盤-Cherry青軸+M23R 無線靜音滑鼠-曜石黑', 2090, 't2-7.jpg', 100, b'1'),
(16, 2, 'irocks K65MS PBT 單色背光 機械式鍵盤-Cherry茶軸', 2590, 't2-8.jpg', 100, b'1'),
(17, 3, 'FOXXRAY 星流響狐電競耳機麥克風(FXR-SAC-05)', 315, 't3-1.jpg', 100, b'1'),
(18, 3, 'FOXXRAY 黑夜響狐電競耳機麥克風(FXR-BAC-39)', 394, 't3-2.jpg', 100, b'1'),
(19, 3, 'E-books S42 電競頭戴耳機麥克風藍', 399, 't3-3.jpg', 100, b'1'),
(20, 3, '羅技 G533 7.1 環繞音效遊戲耳機麥克風', 3490, 't3-4.jpg', 100, b'1'),
(21, 3, 'FOXXRAY 暗星響狐電競耳麥(FXR-BAL-28)', 434, 't3-5.jpg', 100, b'1'),
(22, 3, 'FOXXRAY 聯星響狐電競耳機麥克風(FXR-SAC-01)', 394, 't3-6.jpg', 100, b'1'),
(23, 3, '羅技 G431 7.1 聲道環繞音效電競耳機麥克風', 1990, 't3-7.jpg', 100, b'1'),
(24, 3, 'FOXXRAY 戰斧響狐電競耳機麥克風(FXR-BAL-35)', 394, 't3-8.jpg', 100, b'1'),
(25, 4, 'Cougar美洲獅 RANGER 電競沙發 黑金', 8990, 't4-1.jpg', 100, b'1'),
(26, 4, 'SAMSUNG三星 時尚人體工學電競椅 (三星特仕版)', 9900, 't4-2.jpg', 100, b'1'),
(27, 4, 'SADES賽德斯 DRACO 天龍座 真人體工學 總冠軍賽指定電競', 11900, 't4-3.jpg', 100, b'1'),
(28, 4, 'SADES賽德斯 UNICORN 獨角獸 人體工學電競椅 ANGEL EDITION 天使限量版', 14990, 't4-4.jpg', 100, b'1'),
(29, 4, 'SADES DRACO 天龍座+XPOWER PLUS 極限之力S 送羅技M235無線滑鼠', 13590, 't4-5.jpg', 100, b'1'),
(30, 4, 'SADES DRACO 天龍座 真。人體工學電競椅 (黑藍)', 11900, 't4-6.jpg', 100, b'1'),
(31, 5, '【Philips 飛利浦】DLP4320NT 雙孔USB 快充 電壓顯示-附萬國頭', 599, 't5-1.jpg', 100, b'1'),
(32, 5, '【SHOWHAN】雙USB可折疊2.4A BSMI認証急速充電器/魅惑玫瑰金', 249, 't5-2.jpg', 100, b'1'),
(33, 5, 'Moshi Otto Q 無線充電盤北歐灰', 1590, 't5-3.jpg', 100, b'1'),
(34, 5, 'Apple 20W PD快充插頭 Type-C(USB-C)充電器白色', 468, 't5-4.jpg', 100, b'1'),
(35, 5, 'InfoThink 迪士尼系列粉萌電臀無線充電座 - 邦妮兔', 990, 't5-5.jpg', 100, b'1'),
(36, 5, '【Avier】5A極速四孔充電座芥末綠', 499, 't5-6.jpg', 100, b'1'),
(37, 5, 'Moshi Qubit 迷你 USB-C 充電器 (PD 快充 18W)白色', 690, 't5-7.jpg', 100, b'1'),
(38, 5, 'PHILIPS 飛利浦20W 2port PD充電器 DLP4326C', 499, 't5-8.jpg', 100, b'1'),
(39, 6, 'JOYROOM S-L422 素系列 3A快充一拖三充電線1.2M/黑色', 209, 't6-1.jpg', 100, b'1'),
(40, 6, 'JOYROOM S-L422 素系列 3A快充一拖三充電線1.2M/紅色', 209, 't6-2.jpg', 100, b'1'),
(41, 6, 'ZMI 紫米 Micro USB to Type-C二合一傳輸充電線-30cm (AL511)', 99, 't6-3.jpg', 100, b'1'),
(42, 6, 'ZMI 紫米 Micro USB & Type-C二合一傳輸充電線-100cm (AL501)', 129, 't6-4.jpg', 100, b'1'),
(43, 6, '原廠傳輸線 Sony Type-C USB-C 快充線 QC3.0 高速充電傳輸線 充電線 (UCB20)黑色', 157, 't6-5.jpg', 100, b'1'),
(44, 6, 'Samsung三星 TypeC 原廠傳輸充電線(密封袋裝)單色', 229, 't6-6.jpg', 100, b'1'),
(45, 6, 'Apple Type-C(USB-C) To Lightning PD快充 20W傳輸充電線(1米)白色', 297, 't6-7.jpg', 100, b'1'),
(46, 6, '瑞士｜inCharge 6 六合一傳輸線( MAX加長版 / 太空灰 )', 890, 't6-8.jpg', 100, b'1'),
(47, 7, '2021 四軸手機平板摺疊桌面支架/ 炫酷黑', 312, 't7-1.jpg', 100, b'1'),
(48, 7, '2021 四軸手機平板摺疊桌面支架/ 珍珠白', 312, 't7-2.jpg', 100, b'1'),
(49, 7, '筆電/平板/手機 多功能便攜輕巧折疊支架(IP-MA30) 黑色', 349, 't7-3.jpg', 100, b'1'),
(50, 7, '2021 四軸手機平板摺疊桌面支架/ 墨綠色', 312, 't7-4.jpg', 100, b'1'),
(51, 7, '折疊升降桌面支架 懶人支架 手機/平板通用 黑色', 330, 't7-5.jpg', 100, b'1'),
(52, 7, '折疊升降桌面支架 懶人支架 手機/平板通用 白色', 330, 't7-6.jpg', 100, b'1'),
(53, 7, '筆電/平板/手機 多功能便攜輕巧折疊支架(IP-MA30) 白色', 349, 't7-7.jpg', 100, b'1'),
(54, 7, 'E-books N41 鋁鎂合金三段可拆式手機平板支架白', 399, 't7-8.jpg', 100, b'1');

-- --------------------------------------------------------

--
-- 資料表結構 `product_type`
--

CREATE TABLE `product_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `product_type`
--

INSERT INTO `product_type` (`id`, `type_name`) VALUES
(8, 'Garmin'),
(6, '傳輸充電線'),
(5, '充電器'),
(7, '手機支架'),
(4, '電競椅'),
(1, '電競滑鼠'),
(3, '電競耳機'),
(2, '電競鍵盤');

-- --------------------------------------------------------

--
-- 資料表結構 `svg`
--

CREATE TABLE `svg` (
  `id` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `svg`
--

INSERT INTO `svg` (`id`, `name`) VALUES
('25885', '財團法人伊甸社會福利基金會'),
('583', '財團法人心路社會福利基金會');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `account` (`account`,`oauth_type`,`oauth_id`),
  ADD UNIQUE KEY `reset_password` (`reset_password`),
  ADD UNIQUE KEY `cart_order_id` (`cart_order_id`),
  ADD KEY `account_2` (`account`),
  ADD KEY `oauth_type` (`oauth_type`),
  ADD KEY `oauth_id` (`oauth_id`),
  ADD KEY `activate` (`activate`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `has_commit` (`has_commit`),
  ADD KEY `invoice_type` (`invoice_type`),
  ADD KEY `member_id` (`member_id`);

--
-- 資料表索引 `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`,`order_id`),
  ADD KEY `order_id` (`order_id`);

--
-- 資料表索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_name` (`product_name`),
  ADD KEY `available` (`available`),
  ADD KEY `product_type_id` (`product_type_id`);

--
-- 資料表索引 `product_type`
--
ALTER TABLE `product_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- 資料表索引 `svg`
--
ALTER TABLE `svg`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用資料表 AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- 使用資料表 AUTO_INCREMENT `product_type`
--
ALTER TABLE `product_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`cart_order_id`) REFERENCES `orders` (`id`);

--
-- 資料表的 Constraints `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`);

--
-- 資料表的 Constraints `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- 資料表的 Constraints `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`product_type_id`) REFERENCES `product_type` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
