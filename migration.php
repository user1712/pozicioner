<?php
require 'db.php';
$data = new base();
$data->sql($q = '
CREATE TABLE `attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `attribute` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  UNIQUE KEY `uni` (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=28103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 CREATE TABLE `category` (
  `catalog` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `catalog2` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `catalog3` text COLLATE utf8mb4_unicode_ci NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 CREATE TABLE `category_url` (
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stat` int(11) NOT NULL,
  UNIQUE KEY `uni` (`url`(100))
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 CREATE TABLE `list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `list` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stat` int(11) NOT NULL,
  PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `img` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int(11) NOT NULL,
  UNIQUE KEY `un` (`id`),
  UNIQUE KEY `una` (`name`(100))
 ) ENGINE=InnoDB AUTO_INCREMENT=4396 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 CREATE TABLE `product_url` (
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  UNIQUE KEY `un` (`url`(100))
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
');
