-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2018-11-28 00:28:42
-- 服务器版本： 10.1.36-MariaDB
-- PHP 版本： 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `dev_apiconnection`
--

-- --------------------------------------------------------

--
-- 表的结构 `api_urls`
--

CREATE TABLE `api_urls` (
  `id` int(11) NOT NULL,
  `courier_code` varchar(255) NOT NULL,
  `request_type` int(10) NOT NULL,
  `request_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `api_urls`
--

INSERT INTO `api_urls` (`id`, `courier_code`, `request_type`, `request_url`) VALUES
(1, '4PX', 1, 'http://sandbox.transrush.com.au/agent/createPickupItem'),
(2, '4PX', 2, 'http://sandbox.transrush.com.au/Agent/getTrack'),
(3, '4PX', 3, 'http://sandbox.transrush.com.au/Agent/deleteItem');

-- --------------------------------------------------------

--
-- 表的结构 `couriers`
--

CREATE TABLE `couriers` (
  `code` varchar(50) NOT NULL,
  `api_key` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `couriers`
--

INSERT INTO `couriers` (`code`, `api_key`, `name`) VALUES
('4PX', 'TESTC78C-7923-404C-82CF-CD881539123c', 'si fang ji tuan');

-- --------------------------------------------------------

--
-- 表的结构 `error_messages`
--

CREATE TABLE `error_messages` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `request_type` int(10) NOT NULL DEFAULT '1',
  `courier_code` varchar(50) NOT NULL DEFAULT '4PX',
  `original_msg` text,
  `res_code` varchar(255) NOT NULL,
  `res_msg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `error_messages`
--

INSERT INTO `error_messages` (`id`, `code`, `request_type`, `courier_code`, `original_msg`, `res_code`, `res_msg`) VALUES
(1, '10000', 1, '4PX', '', '0', 'Order Successfully Created, Please Save your order number for further service refferences'),
(2, '12001', 1, '4PX', NULL, 'ERR12001', 'order has not been created, please try again or contact xxx-xxx-xxxx'),
(3, '11004', 1, '4PX', NULL, 'ERR11004', 'you have to provide valid ship order number'),
(4, '12004', 1, '4PX', NULL, 'ERR12004', 'your ship order number has been used, please check and try again'),
(5, '12032', 1, '4PX', NULL, 'ERR12032', 'you have to provide valid ship order number'),
(6, '11005', 1, '4PX', NULL, 'ERR11005', 'you have to provide valid service type code'),
(7, '12005', 1, '4PX', NULL, 'ERR12005', 'you have to provide valid service type code'),
(8, '11006', 1, '4PX', NULL, 'ERR11006', 'sender name can not be empty'),
(9, '11007', 1, '4PX', NULL, 'ERR11007', 'sender mobile can not be empty'),
(10, '11009', 1, '4PX', NULL, 'ERR11009', 'receiver name can not be empty'),
(11, '11010', 1, '4PX', NULL, 'ERR11010', 'receiver province can not be empty'),
(12, '12010', 1, '4PX', NULL, 'ERR12010', 'invalid receiver province'),
(13, '11011', 1, '4PX', NULL, 'ERR11011', 'receiver city name can not be empty'),
(14, '12011', 1, '4PX', NULL, 'ERR12011', 'receiver city name is invalid'),
(15, '11012', 1, '4PX', NULL, 'ERR11012', 'receiver district can not be empty'),
(16, '12012', 1, '4PX', NULL, 'ERR12012', 'receiver district is invalid'),
(17, '11013', 1, '4PX', NULL, 'ERR11013', 'receiver street door number can not be empty'),
(18, '11014', 1, '4PX', NULL, 'ERR11014', 'receiver mobile can not be empty'),
(19, '11015', 1, '4PX', NULL, 'ERR11015', 'receiver ID number can not be empty'),
(20, '12015', 1, '4PX', NULL, 'ERR12015', 'ID number is invalid'),
(21, '11016', 1, '4PX', NULL, 'ERR11016', 'receiver ID front copy can not be empty'),
(22, '11017', 1, '4PX', NULL, 'ERR11017', 'receiver ID back copy can not be empty'),
(23, '11018', 1, '4PX', NULL, 'ERR11018', 'order weight can not be 0'),
(24, '11019', 1, '4PX', NULL, 'ERR11019', 'can not find any items in this order'),
(25, '11020', 1, '4PX', NULL, 'ERR11020', 'order unit weight is invalid'),
(26, '11008', 1, '4PX', NULL, 'ERR11008', 'item declare currency can not be empty'),
(27, '12008', 1, '4PX', NULL, 'ERR12008', 'item declare currency is invalid'),
(28, '11020', 1, '4PX', NULL, 'ERR11020', 'item name can not be empty'),
(29, '11021', 1, '4PX', NULL, 'ERR11021', 'item quantity can not be empty'),
(30, '11022', 1, '4PX', NULL, 'ERR11022', 'item specifications can not be empty'),
(31, '11024', 1, '4PX', NULL, 'ERR11024', 'item declare type can not be empty'),
(32, '12024', 1, '4PX', NULL, 'ERR12024', 'item declare type is invalid'),
(33, '12025', 1, '4PX', NULL, 'ERR12025', 'item is not promised for delivery'),
(34, '12026', 1, '4PX', NULL, 'ERR12026', 'order cost is over the limitation'),
(35, '12027', 1, '4PX', NULL, 'ERR12027', 'receiver name has to be Chinese, as receiver\'s country is China'),
(36, '12028', 1, '4PX', NULL, 'ERR12028', 'product, service, end delivery and product type is not matching'),
(37, '12029', 1, '4PX', NULL, 'ERR12029', 'item information is invalid'),
(38, '12030', 1, '4PX', NULL, 'ERR12030', 'insurance type is invalid'),
(39, '12031', 1, '4PX', NULL, 'ERR12031', 'item brand can not be empty'),
(40, '12033', 1, '4PX', NULL, 'ERR12033', 'shop code can not be empty, as your company has multiple shops'),
(41, '12034', 1, '4PX', NULL, 'ERR12034', 'trace source number is invalid'),
(42, '12035', 1, '4PX', NULL, 'ERR12035', 'trace source number can only be used in Australlian'),
(43, '11025', 1, '4PX', NULL, 'ERR11025', 'insurance expense can not be empty'),
(44, '12036', 1, '4PX', NULL, 'ERR12036', 'insurance expense is not valid'),
(45, '12037', 1, '4PX', NULL, 'ERR12037', 'country code is invalid'),
(46, '13001', 1, '4PX', NULL, 'ERR13001', 'fail to create order, try again or contanct XXXX-XXX-XXX'),
(47, '14000', 1, '4PX', NULL, 'ERR14000', 'fail to find the destination, please check your input'),
(48, 'A0001', 1, '4PX', NULL, 'ERRA0001', 'ItemSKU is missing'),
(49, 'A0002', 1, '4PX', NULL, 'ERRA0002', 'ItemSKU is not found'),
(50, 'A0003', 1, '4PX', NULL, 'ERRA0003', 'ItemSKU is valid yet'),
(51, '10000', 2, '4PX', NULL, '0', 'Order Found'),
(52, '13005', 2, '4PX', NULL, '1', 'Order can not find!'),
(53, '12002', 2, '4PX', NULL, 'ERR12002', 'Token is incorrect'),
(54, '11002', 2, '4PX', NULL, 'ERR11002', 'Token can not be null'),
(55, '11003', 2, '4PX', NULL, 'ERR11003', 'Data is missing'),
(56, '13004', 2, '4PX', NULL, 'ERR13004', 'Items have not been picked up yet'),
(57, '11004', 2, '4PX', NULL, 'ERR11004', 'ShipperOrderNo'),
(58, '10000', 3, '4PX', NULL, '0', 'Success! Order Deleted!'),
(59, '11000', 3, '4PX', NULL, 'ERR11000', 'API Params can not be null'),
(60, '11001', 3, '4PX', NULL, 'ERR11001', 'Request body is not JSON format'),
(61, '11002', 3, '4PX', NULL, 'ERR11002', 'Token can not be null'),
(62, '12002', 3, '4PX', NULL, 'ERR12002', 'Token is incorrect'),
(63, '12003', 3, '4PX', NULL, 'ERR12003', 'API interface is not authorized'),
(64, '11004', 3, '4PX', NULL, '1', 'Fail to delete the order');

-- --------------------------------------------------------

--
-- 表的结构 `request_types`
--

CREATE TABLE `request_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `request_types`
--

INSERT INTO `request_types` (`id`, `name`) VALUES
(1, 'create'),
(2, 'track'),
(3, 'delete');

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `branchId` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `branchKey` varchar(50) NOT NULL,
  `status` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`branchId`, `name`, `address`, `branchKey`, `status`) VALUES
('1', 'testShop1', 'testShop1Address', 'testShop1branchKey', b'1'),
('SHOP111', 'test shop 2', 'test shop 2 address', '123456', b'1'),
('SHOP333', 'Shop3', 'shop3 burwood', '123456', b'0');

--
-- 转储表的索引
--

--
-- 表的索引 `api_urls`
--
ALTER TABLE `api_urls`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `couriers`
--
ALTER TABLE `couriers`
  ADD PRIMARY KEY (`code`);

--
-- 表的索引 `error_messages`
--
ALTER TABLE `error_messages`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `request_types`
--
ALTER TABLE `request_types`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`branchId`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `api_urls`
--
ALTER TABLE `api_urls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `error_messages`
--
ALTER TABLE `error_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- 使用表AUTO_INCREMENT `request_types`
--
ALTER TABLE `request_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
