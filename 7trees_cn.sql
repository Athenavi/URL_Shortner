-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2023-08-29 08:25:12
-- 服务器版本： 5.7.40-log
-- PHP 版本： 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `url_7trees_cn`
--

-- --------------------------------------------------------

--
-- 表的结构 `invitecode`
--

CREATE TABLE `invitecode` (
  `uuid` char(36) NOT NULL,
  `code` char(4) NOT NULL,
  `is_used` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `invitecode`
--

INSERT INTO `invitecode` (`uuid`, `code`, `is_used`) VALUES
('1', '9988', 0),
('2', '8989', 0);

-- --------------------------------------------------------

--
-- 表的结构 `IPCount`
--

CREATE TABLE `IPCount` (
  `visitId` int(11) NOT NULL,
  `short_url` varchar(255) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `New_Old` char(1) DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `opentimes`
--

CREATE TABLE `opentimes` (
  `id` int(11) NOT NULL,
  `short_url` varchar(10) NOT NULL,
  `response_count` int(11) NOT NULL DEFAULT '0',
  `first_response_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `urls`
--

CREATE TABLE `urls` (
  `id` int(11) NOT NULL,
  `long_url` varchar(255) NOT NULL,
  `short_url` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ifAdmin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `ifAdmin`) VALUES
(1, 'test', '123456', 1);

--
-- 转储表的索引
--

--
-- 表的索引 `invitecode`
--
ALTER TABLE `invitecode`
  ADD PRIMARY KEY (`uuid`);

--
-- 表的索引 `IPCount`
--
ALTER TABLE `IPCount`
  ADD PRIMARY KEY (`visitId`);

--
-- 表的索引 `opentimes`
--
ALTER TABLE `opentimes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_url_unique` (`short_url`);

--
-- 表的索引 `urls`
--
ALTER TABLE `urls`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `IPCount`
--
ALTER TABLE `IPCount`
  MODIFY `visitId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- 使用表AUTO_INCREMENT `opentimes`
--
ALTER TABLE `opentimes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- 使用表AUTO_INCREMENT `urls`
--
ALTER TABLE `urls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
