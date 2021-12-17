-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 17 Ara 2021, 21:00:33
-- Sunucu sürümü: 10.4.21-MariaDB
-- PHP Sürümü: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `ideasoft`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `customers`
--

CREATE TABLE `customers` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `since` int(10) NOT NULL,
  `revenue` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `customers`
--

INSERT INTO `customers` (`id`, `name`, `since`, `revenue`) VALUES
(1, 'Türker Jöntürk', 2014, 492.12),
(2, 'Kaptan Devopuz', 2015, 1505.95),
(3, 'İsa Sonuyumaz', 2016, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int(10) NOT NULL,
  `customerId` int(10) NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `customerId`, `total`) VALUES
(1, 1, 83.58),
(2, 1, 1132.425),
(3, 2, 160.35),
(5, 3, 352.35),
(6, 2, 160.35),
(7, 1, 890.1),
(8, 1, 34.08);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) NOT NULL,
  `orderId` int(10) NOT NULL,
  `productId` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `unitPrice` double NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `order_items`
--

INSERT INTO `order_items` (`id`, `orderId`, `productId`, `quantity`, `unitPrice`, `total`) VALUES
(1, 1, 2, 1, 49.5, 49.5),
(2, 1, 3, 1, 11.28, 11.28),
(3, 1, 4, 1, 22.8, 22.8),
(4, 2, 1, 1, 120.75, 120.75),
(5, 2, 2, 3, 49.5, 148.5),
(6, 2, 5, 1, 1000, 1000),
(7, 3, 1, 1, 120.75, 120.75),
(8, 3, 2, 1, 49.5, 49.5),
(11, 5, 1, 3, 120.75, 362.25),
(12, 6, 1, 1, 120.75, 120.75),
(13, 6, 2, 1, 49.5, 49.5),
(14, 7, 5, 1, 1000, 1000),
(15, 8, 3, 1, 11.28, 11.28),
(16, 8, 4, 1, 22.8, 22.8);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` int(10) NOT NULL,
  `price` double NOT NULL,
  `stock` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `stock`) VALUES
(1, 'Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti', 1, 120.75, 10),
(2, 'Reko Mini Tamir Hassas Tornavida Seti 32\'li', 1, 49.5, 10),
(3, 'Viko Karre Anahtar - Beyaz', 2, 11.28, 10),
(4, 'Legrand Salbei Anahtar, Alüminyum', 2, 22.8, 10),
(5, 'Schneider Asfora Beyaz, Komütatör', 2, 1000, 10);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
