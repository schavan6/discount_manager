SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` varchar(30) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `isPercentage` tinyint(1),
  `value` int(11) NOT NULL,
  `isActive` tinyint(1),
  PRIMARY KEY (`id`)
);

CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(300) NOT NULL,
  `price` int(11) NOT NULL,
   PRIMARY KEY (`id`)
);

CREATE TABLE `product_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
   PRIMARY KEY (`id`)
);

INSERT INTO `discount` (`name`, `isPercentage`, `value`, `isActive`) VALUES
('15OFF', 1, 15, 1);

INSERT INTO `product` (`name`, `description`, `price`) VALUES
('Original Cheerios', 'A heart-healthy lifestyle can be fun, easy and delicious. Our delicious Os are made from whole grain oats which contain beta-glucan, a soluble fiber that can help lower cholesterol as part of a heart-healthy diet!', 100);

COMMIT;