-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-11-22 21:08:10
-- 服务器版本： 5.7.19-0ubuntu0.16.04.1
-- PHP Version: 5.6.31-6+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wechatpay`
--

-- --------------------------------------------------------

--
-- 表的结构 `stjz_activity`
--

CREATE TABLE `stjz_activity` (
  `id` int(11) NOT NULL,
  `total` int(20) NOT NULL,
  `num` int(20) NOT NULL,
  `min` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `stjz_activity`
--

INSERT INTO `stjz_activity` (`id`, `total`, `num`, `min`) VALUES
(1, 10, 10, 1);

-- --------------------------------------------------------

--
-- 表的结构 `stjz_user`
--

CREATE TABLE `stjz_user` (
  `id` int(20) NOT NULL,
  `openid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `luck_money` int(30) NOT NULL DEFAULT '0',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `stjz_user`
--

INSERT INTO `stjz_user` (`id`, `openid`, `luck_money`, `name`, `tel`, `time`) VALUES
(1, 'wxeae7cae254903553', 1, '王恒', '17601322524', '2017-11-22 13:02:58'),
(2, 'wxeae7cae254903553', 1, '王恒', '17601322524', '2017-11-22 13:04:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `stjz_activity`
--
ALTER TABLE `stjz_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stjz_user`
--
ALTER TABLE `stjz_user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `stjz_activity`
--
ALTER TABLE `stjz_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `stjz_user`
--
ALTER TABLE `stjz_user`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
