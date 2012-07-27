-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 12 月 31 日 05:07
-- 服务器版本: 5.1.41
-- PHP 版本: 5.3.2-1ubuntu4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `altcake`
--

-- --------------------------------------------------------

--
-- 表的结构 `agent_site_mappings`
--

CREATE TABLE `agent_site_mappings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL,
  `agentid` int(11) NOT NULL,
  `campaignid` varchar(64) COLLATE ascii_bin NOT NULL,
  `flag` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'the value 0 is only useful for site "iml", and all the other sites should be 1 as default',
  PRIMARY KEY (`id`),
  KEY `agentid` (`agentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `bulletins`
--

CREATE TABLE `bulletins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `archdate` datetime DEFAULT NULL,
  `title` int(11) DEFAULT NULL,
  `info` mediumtext COLLATE ascii_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `chat_logs`
--

CREATE TABLE `chat_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agentid` int(11) NOT NULL,
  `clientusername` varchar(64) COLLATE ascii_bin NOT NULL,
  `submittime` datetime NOT NULL,
  `conversation` text COLLATE ascii_bin NOT NULL,
  `siteid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `com_fees`
--

CREATE TABLE `com_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feeid` int(11) NOT NULL,
  `companyid` int(11) NOT NULL,
  `ownprice` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `feeid` (`feeid`,`companyid`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `online_logs`
--

CREATE TABLE `online_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accountid` int(11) NOT NULL,
  `intime` datetime NOT NULL,
  `inip` varchar(15) COLLATE ascii_bin DEFAULT NULL,
  `terminalcookieid` int(11) DEFAULT NULL,
  `outtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `accountid` (`accountid`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `site_excludings`
--

CREATE TABLE `site_excludings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyid` int(11) NOT NULL,
  `agentid` int(11) NOT NULL,
  `siteid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `site_manuals`
--

CREATE TABLE `site_manuals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL,
  `html` text COLLATE ascii_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `terminal_cookies`
--

CREATE TABLE `terminal_cookies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cookie` char(32) COLLATE ascii_bin DEFAULT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `tmp_com_fees`
--

CREATE TABLE `tmp_com_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feeid` int(11) NOT NULL,
  `companyid` int(11) NOT NULL,
  `ownprice` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) COLLATE ascii_bin NOT NULL,
  `username4m` varchar(48) COLLATE ascii_bin NOT NULL DEFAULT '000',
  `password` varbinary(48) NOT NULL,
  `originalpwd` varchar(48) COLLATE ascii_bin NOT NULL,
  `role` tinyint(4) NOT NULL COMMENT '0-admin,1-company,2-agent',
  `regtime` datetime NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-suspended,1-activated',
  `online` int(11) NOT NULL DEFAULT '-1' COMMENT '-1 offline, >0 online id in "online_logs"',
  `lastlogintime` datetime DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `username4m` (`username4m`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) COLLATE ascii_bin NOT NULL,
  `notes` mediumtext COLLATE ascii_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `agents`
--

CREATE TABLE `agents` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(64) COLLATE ascii_bin NOT NULL,
  `paytoname` varchar(64) COLLATE ascii_bin NOT NULL,
  `keystring` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `companyid` int(11) NOT NULL,
  `ag1stname` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `aglastname` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `email` varchar(128) COLLATE ascii_bin DEFAULT NULL,
  `street` varchar(256) COLLATE ascii_bin DEFAULT NULL COMMENT 'street name & number',
  `city` varchar(256) COLLATE ascii_bin DEFAULT NULL,
  `state` varchar(256) COLLATE ascii_bin DEFAULT NULL COMMENT 'state & zip',
  `country` varchar(2) COLLATE ascii_bin DEFAULT NULL,
  `im` varchar(128) COLLATE ascii_bin DEFAULT NULL,
  `cellphone` varchar(32) COLLATE ascii_bin DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `companyid` (`companyid`),
  KEY `countryabbr` (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `clickouts`
--

CREATE TABLE `clickouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `linkid` int(11) DEFAULT NULL,
  `agentid` int(11) DEFAULT NULL,
  `clicktime` datetime NOT NULL,
  `fromip` varchar(15) COLLATE ascii_bin NOT NULL,
  `siteid` int(11) DEFAULT NULL,
  `typeid` int(11) DEFAULT NULL,
  `link` varchar(256) COLLATE ascii_bin DEFAULT NULL,
  `url` varchar(256) COLLATE ascii_bin DEFAULT NULL,
  `referer` varchar(128) COLLATE ascii_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `companies`
--

CREATE TABLE `companies` (
  `id` int(11) unsigned NOT NULL,
  `officename` varchar(128) COLLATE ascii_bin DEFAULT NULL,
  `man1stname` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `manlastname` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `manemail` varchar(128) COLLATE ascii_bin DEFAULT NULL,
  `mancellphone` varchar(32) COLLATE ascii_bin DEFAULT NULL,
  `street` varchar(128) COLLATE ascii_bin NOT NULL COMMENT 'street name & number',
  `city` varchar(128) COLLATE ascii_bin NOT NULL,
  `state` varchar(128) COLLATE ascii_bin NOT NULL COMMENT 'state & zip',
  `country` varchar(2) COLLATE ascii_bin NOT NULL,
  `contactname` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `payeename` varchar(64) COLLATE ascii_bin DEFAULT NULL COMMENT 'for the company',
  `payeeaddr1` varchar(128) COLLATE ascii_bin DEFAULT NULL,
  `payeeaddr2` varchar(128) COLLATE ascii_bin DEFAULT NULL,
  `payeecity` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `payeestate` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `payeezipcode` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `payeecountry` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `payeeemail` varchar(128) COLLATE ascii_bin DEFAULT NULL,
  `payouttype` tinyint(4) DEFAULT NULL COMMENT '0-pay by check, 1-pay by webstern uion, 2-pay by wire',
  `pps` decimal(8,2) NOT NULL DEFAULT '10.00',
  `paymentinfo` text COLLATE ascii_bin,
  `agentnotes` text COLLATE ascii_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `countries`
--

CREATE TABLE `countries` (
  `fullname` varchar(64) COLLATE ascii_bin NOT NULL,
  `abbr` varchar(2) COLLATE ascii_bin NOT NULL,
  KEY `abbr` (`abbr`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `earning` decimal(8,2) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL,
  `agentid` int(11) NOT NULL,
  `url` varchar(768) COLLATE ascii_bin NOT NULL,
  `typeid` int(11) NOT NULL,
  `remark` varchar(768) COLLATE ascii_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 means suspended, 1 means activated',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `run_stats`
--

CREATE TABLE `run_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `runtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `sites`
--

CREATE TABLE `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostname` varchar(128) COLLATE ascii_bin NOT NULL COMMENT 'for admin only',
  `sitename` varchar(128) COLLATE ascii_bin NOT NULL COMMENT 'for company & agent',
  `abbr` varchar(5) COLLATE ascii_bin DEFAULT NULL COMMENT 'for admin only',
  `srcdriver` varchar(64) COLLATE ascii_bin DEFAULT NULL COMMENT 'php fille name which fecthing & saving rss feed data src',
  `url` varchar(128) COLLATE ascii_bin NOT NULL,
  `contactname` varchar(64) COLLATE ascii_bin NOT NULL,
  `email` varchar(64) COLLATE ascii_bin NOT NULL,
  `phonenums` varchar(64) COLLATE ascii_bin NOT NULL,
  `type` varchar(64) COLLATE ascii_bin NOT NULL,
  `payouts` decimal(8,2) NOT NULL,
  `notes` text COLLATE ascii_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sitename` (`hostname`),
  UNIQUE KEY `abbr` (`abbr`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `stats`
--

CREATE TABLE `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agentid` int(11) NOT NULL,
  `raws` int(11) NOT NULL,
  `uniques` int(11) NOT NULL,
  `chargebacks` int(11) NOT NULL,
  `signups` int(11) DEFAULT NULL,
  `frauds` int(11) DEFAULT NULL,
  `sales_number` int(11) NOT NULL,
  `typeid` int(11) NOT NULL,
  `siteid` int(11) NOT NULL,
  `campaignid` varchar(64) COLLATE ascii_bin DEFAULT NULL,
  `trxtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agentid` (`agentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `tmp_stats`
--

CREATE TABLE `tmp_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `runid` int(11) NOT NULL,
  `bygroup` tinyint(4) DEFAULT NULL COMMENT '1 by date, 2 by company, 3 by agent, 4 by agent detail',
  `trxtime` datetime DEFAULT NULL,
  `companyid` int(11) NOT NULL,
  `officename` varchar(64) COLLATE ascii_bin NOT NULL,
  `comscount` int(11) NOT NULL,
  `agentid` int(11) NOT NULL,
  `username` varchar(64) COLLATE ascii_bin NOT NULL,
  `username4m` varchar(48) COLLATE ascii_bin DEFAULT NULL,
  `agscount` int(11) NOT NULL,
  `siteid` int(11) NOT NULL,
  `sitename` varchar(64) COLLATE ascii_bin NOT NULL,
  `sitescount` int(11) NOT NULL,
  `typeid` int(11) NOT NULL,
  `typename` varchar(64) COLLATE ascii_bin NOT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `earning` decimal(8,2) DEFAULT NULL,
  `typescount` int(11) NOT NULL,
  `raws` int(11) NOT NULL,
  `uniques` int(11) NOT NULL,
  `chargebacks` int(11) NOT NULL,
  `signups` int(11) DEFAULT NULL,
  `frauds` int(11) DEFAULT NULL,
  `sales_type1` int(11) DEFAULT NULL,
  `sales_type2` int(11) DEFAULT NULL,
  `sales_type3` int(11) DEFAULT NULL,
  `sales_type4` int(11) DEFAULT NULL,
  `net` int(11) DEFAULT NULL,
  `payouts` decimal(8,2) DEFAULT NULL,
  `earnings` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `runid` (`runid`)
) ENGINE=MEMORY  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- 表的结构 `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL,
  `typename` varchar(64) COLLATE ascii_bin NOT NULL,
  `url` varchar(128) COLLATE ascii_bin DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `typename` (`typename`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii COLLATE=ascii_bin;

--
-- 视图结构 `view_admins`
--
DROP TABLE IF EXISTS `view_admins`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marijana`@`localhost` SQL SECURITY DEFINER VIEW `view_admins` AS select `a`.`id` AS `id`,`a`.`username` AS `username`,`a`.`password` AS `password`,`a`.`originalpwd` AS `originalpwd`,`a`.`role` AS `role`,`a`.`regtime` AS `regtime`,`a`.`status` AS `status`,`a`.`online` AS `online`,`a`.`level` AS `level`,`b`.`email` AS `email`,`b`.`notes` AS `notes` from (`accounts` `a` join `admins` `b`) where (`a`.`id` = `b`.`id`);

-- --------------------------------------------------------

--
-- 视图结构 `view_agents`
--
DROP TABLE IF EXISTS `view_agents`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marijana`@`localhost` SQL SECURITY DEFINER VIEW `view_agents` AS select `agents`.`id` AS `id`,`accounts`.`username` AS `username`,`accounts`.`username4m` AS `username4m`,`accounts`.`originalpwd` AS `originalpwd`,`accounts`.`regtime` AS `regtime`,`accounts`.`lastlogintime` AS `lastlogintime`,(select count(`online_logs`.`id`) AS `count(online_logs.id)` from `online_logs` where (`online_logs`.`accountid` = `accounts`.`id`)) AS `logintimes`,`accounts`.`status` AS `status`,`accounts`.`online` AS `online`,`companies`.`id` AS `companyid`,`companies`.`officename` AS `officename`,`agents`.`name` AS `name`,`agents`.`paytoname` AS `paytoname`,`agents`.`keystring` AS `keystring`,`agents`.`ag1stname` AS `ag1stname`,`agents`.`aglastname` AS `aglastname`,`agents`.`email` AS `email`,`agents`.`street` AS `street`,`agents`.`city` AS `city`,`agents`.`state` AS `state`,`countries`.`fullname` AS `country`,`agents`.`im` AS `im`,`agents`.`cellphone` AS `cellphone`,(select count(`agent_site_mappings`.`id`) AS `count(id)` from `agent_site_mappings` where (`agent_site_mappings`.`agentid` = `agents`.`id`)) AS `campaigns` from (((`agents` join `companies`) join `accounts`) join `countries`) where ((`agents`.`id` = `accounts`.`id`) and (`agents`.`companyid` = `companies`.`id`) and (`agents`.`country` = `countries`.`abbr`));

-- --------------------------------------------------------

--
-- 视图结构 `view_clickouts`
--
DROP TABLE IF EXISTS `view_clickouts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marijana`@`%` SQL SECURITY DEFINER VIEW `view_clickouts` AS select `c`.`id` AS `companyid`,`c`.`officename` AS `officename`,`a`.`agentid` AS `agentid`,`d`.`username` AS `username`,`a`.`clicktime` AS `clicktime`,`a`.`fromip` AS `fromip`,`a`.`referer` AS `referer`,`a`.`siteid` AS `siteid`,(select `sites`.`sitename` AS `sitename` from `sites` where (`sites`.`id` = `a`.`siteid`)) AS `sitename`,`a`.`typeid` AS `typeid`,(select `types`.`typename` AS `typename` from `types` where (`types`.`id` = `a`.`typeid`)) AS `typename`,`a`.`url` AS `url` from (((`clickouts` `a` join `agents` `b`) join `companies` `c`) join `accounts` `d`) where ((`a`.`agentid` = `b`.`id`) and (`b`.`id` = `d`.`id`) and (`b`.`companyid` = `c`.`id`));

-- --------------------------------------------------------

--
-- 视图结构 `view_companies`
--
DROP TABLE IF EXISTS `view_companies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marijana`@`localhost` SQL SECURITY DEFINER VIEW `view_companies` AS select count(`a`.`id`) AS `agenttotal`,`a`.`companyid` AS `companyid`,`b`.`officename` AS `officename`,`c`.`username` AS `username`,`c`.`username4m` AS `username4m`,`c`.`originalpwd` AS `originalpwd`,`c`.`regtime` AS `regtime`,`c`.`status` AS `status`,`c`.`online` AS `online`,`b`.`street` AS `street`,`b`.`city` AS `city`,`b`.`state` AS `state`,`d`.`fullname` AS `counrty`,`b`.`payeename` AS `payeename`,`b`.`contactname` AS `contactname`,`b`.`man1stname` AS `man1stname`,`b`.`manlastname` AS `manlastname`,`b`.`manemail` AS `manemail`,`b`.`mancellphone` AS `mancellphone` from (((`agents` `a` join `companies` `b`) join `accounts` `c`) join `countries` `d`) where ((`a`.`companyid` = `b`.`id`) and (`b`.`id` = `c`.`id`) and (`b`.`country` = `d`.`abbr`)) group by `a`.`companyid` union select 0 AS `0`,`b`.`id` AS `companyid`,`b`.`officename` AS `officename`,`c`.`username` AS `username`,`c`.`username4m` AS `username4m`,`c`.`originalpwd` AS `originalpwd`,`c`.`regtime` AS `regtime`,`c`.`status` AS `status`,`c`.`online` AS `online`,`b`.`street` AS `street`,`b`.`city` AS `city`,`b`.`state` AS `state`,`d`.`fullname` AS `counrty`,`b`.`payeename` AS `payeename`,`b`.`contactname` AS `contactname`,`b`.`man1stname` AS `man1stname`,`b`.`manlastname` AS `manlastname`,`b`.`manemail` AS `manemail`,`b`.`mancellphone` AS `mancellphone` from ((`companies` `b` join `accounts` `c`) join `countries` `d`) where ((`b`.`id` = `c`.`id`) and (`b`.`country` = `d`.`abbr`) and (not(`b`.`id` in (select distinct `agents`.`companyid` AS `companyid` from `agents`))));

-- --------------------------------------------------------

--
-- 视图结构 `view_links`
--
DROP TABLE IF EXISTS `view_links`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_links` AS select `a`.`id` AS `id`,`b`.`id` AS `agentid`,`f`.`username` AS `agusername`,`a`.`url` AS `url`,`a`.`remark` AS `remark`,`a`.`status` AS `status`,`c`.`id` AS `companyid`,`c`.`officename` AS `officename`,`d`.`id` AS `siteid`,`d`.`hostname` AS `hostname`,`d`.`sitename` AS `sitename`,`d`.`srcdriver` AS `srcdriver`,`e`.`id` AS `typeid`,`e`.`typename` AS `typename` from (((((`links` `a` join `agents` `b`) join `companies` `c`) join `sites` `d`) join `types` `e`) join `accounts` `f`) where ((`a`.`agentid` = `b`.`id`) and (`b`.`companyid` = `c`.`id`) and (`a`.`siteid` = `d`.`id`) and (`a`.`typeid` = `e`.`id`) and (`a`.`siteid` = `e`.`siteid`) and (`b`.`id` = `f`.`id`));

-- --------------------------------------------------------

--
-- 视图结构 `view_sites`
--
DROP TABLE IF EXISTS `view_sites`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marijana`@`%` SQL SECURITY DEFINER VIEW `view_sites` AS select `s`.`id` AS `id`,`s`.`hostname` AS `hostname`,`s`.`sitename` AS `sitename`,concat(`s`.`sitename`,' (',`s`.`type`,')') AS `sitenametype`,`s`.`abbr` AS `abbr`,`s`.`srcdriver` AS `srcdriver`,`s`.`url` AS `url`,`s`.`contactname` AS `contactname`,`s`.`email` AS `email`,`s`.`phonenums` AS `phonenums`,`s`.`type` AS `type`,`s`.`payouts` AS `payouts`,`s`.`notes` AS `notes`,`s`.`status` AS `status`,(select count(`links`.`id`) AS `count(id)` from `links` where (`links`.`siteid` = `s`.`id`)) AS `linkstotal`,(select count(`types`.`id`) AS `count(id)` from `types` where (`types`.`siteid` = `s`.`id`)) AS `typestotal` from `sites` `s`;

-- --------------------------------------------------------

--
-- 视图结构 `view_stats`
--
DROP TABLE IF EXISTS `view_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marijana`@`%` SQL SECURITY DEFINER VIEW `view_stats` AS select `a`.`trxtime` AS `trxtime`,`b`.`companyid` AS `companyid`,`c`.`officename` AS `officename`,`a`.`agentid` AS `agentid`,`d`.`username` AS `username`,`d`.`username4m` AS `username4m`,`a`.`siteid` AS `siteid`,`e`.`sitename` AS `sitename`,`a`.`typeid` AS `typeid`,`f`.`typename` AS `typename`,`g`.`price` AS `price`,`g`.`earning` AS `earning`,`a`.`raws` AS `raws`,`a`.`uniques` AS `uniques`,`a`.`chargebacks` AS `chargebacks`,`a`.`signups` AS `signups`,`a`.`frauds` AS `frauds`,`a`.`sales_number` AS `sales_number`,(`a`.`sales_number` - `a`.`chargebacks`) AS `net`,((`a`.`sales_number` - `a`.`chargebacks`) * `h`.`ownprice`) AS `payouts`,((`a`.`sales_number` - `a`.`chargebacks`) * `g`.`earning`) AS `earnings` from (((((((`stats` `a` join `agents` `b`) join `companies` `c`) join `accounts` `d`) join `sites` `e`) join `types` `f`) join `fees` `g`) join `tmp_com_fees` `h`) where ((`a`.`agentid` = `b`.`id`) and (`b`.`companyid` = `c`.`id`) and (`a`.`agentid` = `d`.`id`) and (`a`.`siteid` = `e`.`id`) and (`a`.`typeid` = `f`.`id`) and (`f`.`id` = `g`.`typeid`) and (`a`.`trxtime` >= `g`.`start`) and (`a`.`trxtime` <= `g`.`end`) and (`g`.`id` = `h`.`feeid`) and (`c`.`id` = `h`.`companyid`));

-- --------------------------------------------------------

--
-- 视图结构 `view_types`
--
DROP TABLE IF EXISTS `view_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marijana`@`localhost` SQL SECURITY DEFINER VIEW `view_types` AS select `b`.`hostname` AS `hostname`,`b`.`sitename` AS `sitename`,`a`.`id` AS `id`,`a`.`siteid` AS `siteid`,`a`.`typename` AS `typename`,`a`.`url` AS `url`,`a`.`status` AS `status`,`c`.`price` AS `price`,`c`.`earning` AS `earning`,`c`.`start` AS `start`,`c`.`end` AS `end` from ((`types` `a` join `sites` `b`) join `fees` `c`) where ((`a`.`siteid` = `b`.`id`) and (`a`.`id` = `c`.`typeid`));

-- --------------------------------------------------------

--
-- 视图结构 `view_chat_logs`
--
DROP TABLE IF EXISTS `view_chat_logs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marijana`@`localhost` SQL SECURITY DEFINER VIEW `view_chat_logs` AS select `b`.`companyid` AS `companyid`,`c`.`officename` AS `officename`,`a`.`agentid` AS `agentid`,`d`.`username` AS `username`,`d`.`username4m` AS `username4m`,`a`.`clientusername` AS `clientusername`,concat(left(`a`.`conversation`,500),_ascii'\r\n...') AS `conversation`,`a`.`submittime` AS `submittime`,`a`.`siteid` AS `siteid`,`e`.`sitename` AS `sitename` from ((((`chat_logs` `a` join `agents` `b`) join `companies` `c`) join `accounts` `d`) join `sites` `e`) where ((`a`.`agentid` = `b`.`id`) and (`b`.`companyid` = `c`.`id`) and (`b`.`id` = `d`.`id`) and (`a`.`siteid` = `e`.`id`));

-- --------------------------------------------------------

--
-- 视图结构 `view_mappings`
--
DROP TABLE IF EXISTS `view_mappings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marijana`@`localhost` SQL SECURITY DEFINER VIEW `view_mappings` AS select `a`.`id` AS `id`,`a`.`siteid` AS `siteid`,`a`.`agentid` AS `agentid`,`a`.`campaignid` AS `campaignid`,`a`.`flag` AS `flag`,`b`.`hostname` AS `hostname`,`b`.`abbr` AS `abbr`,`b`.`sitename` AS `sitename`,`c`.`username` AS `username` from ((`agent_site_mappings` `a` join `sites` `b`) join `accounts` `c`) where ((`a`.`siteid` = `b`.`id`) and (`a`.`agentid` = `c`.`id`));

-- --------------------------------------------------------

--
-- 视图结构 `view_online_logs`
--
DROP TABLE IF EXISTS `view_online_logs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marijana`@`%` SQL SECURITY DEFINER VIEW `view_online_logs` AS select `a`.`id` AS `id`,`a`.`accountid` AS `accountid`,`b`.`username` AS `username`,`b`.`username4m` AS `username4m`,`a`.`intime` AS `intime`,`a`.`inip` AS `inip`,`a`.`outtime` AS `outtime` from (`online_logs` `a` join `accounts` `b`) where (`a`.`accountid` = `b`.`id`);


INSERT INTO `countries` (`fullname`, `abbr`) VALUES
('Afghanistan', 'AF'),
('Aland Islands', 'AX'),
('Albania', 'AL'),
('Algeria', 'DZ'),
('American Samoa', 'AS'),
('Andorra', 'AD'),
('Angola', 'AO'),
('Anguilla', 'AI'),
('Antarctica', 'AQ'),
('Antigua And Barbuda', 'AG'),
('Argentina', 'AR'),
('Armenia', 'AM'),
('Aruba', 'AW'),
('Australia', 'AU'),
('Austria', 'AT'),
('Azerbaijan', 'AZ'),
('Bahamas', 'BS'),
('Bahrain', 'BH'),
('Bangladesh', 'BD'),
('Barbados', 'BB'),
('Belarus', 'BY'),
('Belgium', 'BE'),
('Belize', 'BZ'),
('Benin', 'BJ'),
('Bermuda', 'BM'),
('Bhutan', 'BT'),
('Bolivia', 'BO'),
('Bosnia And Herzegovina', 'BA'),
('Botswana', 'BW'),
('Bouvet Island', 'BV'),
('Brazil', 'BR'),
('British Indian Ocean Territory', 'IO'),
('Brunei Darussalam', 'BN'),
('Bulgaria', 'BG'),
('Burkina Faso', 'BF'),
('Burundi', 'BI'),
('Cambodia', 'KH'),
('Cameroon', 'CM'),
('Canada', 'CA'),
('Cape Verde', 'CV'),
('Cayman Islands', 'KY'),
('Central African Republic', 'CF'),
('Chad', 'TD'),
('Chile', 'CL'),
('China', 'CN'),
('Christmas Island', 'CX'),
('Cocos (keeling) Islands', 'CC'),
('Colombia', 'CO'),
('Comoros', 'KM'),
('Congo, The Democratic Republic Of The', 'CD'),
('Congo', 'CG'),
('Cook Islands', 'CK'),
('Costa Rica', 'CR'),
('COte D''ivoire', 'CI'),
('Croatia', 'HR'),
('Cuba', 'CU'),
('Cyprus', 'CY'),
('Czech Republic', 'CZ'),
('Denmark', 'DK'),
('Djibouti', 'DJ'),
('Dominica', 'DM'),
('Dominican Republic', 'DO'),
('Ecuador', 'EC'),
('Egypt', 'EG'),
('El Salvador', 'SV'),
('Equatorial Guinea', 'GQ'),
('Eritrea', 'ER'),
('Estonia', 'EE'),
('Ethiopia', 'ET'),
('Falkland Islands (malvinas)', 'FK'),
('Faroe Islands', 'FO'),
('Fiji', 'FJ'),
('Finland', 'FI'),
('France', 'FR'),
('French Guiana', 'GF'),
('French Polynesia', 'PF'),
('French Southern Territories', 'TF'),
('Gabon', 'GA'),
('Gambia', 'GM'),
('Georgia', 'GE'),
('Germany', 'DE'),
('Ghana', 'GH'),
('Gibraltar', 'GI'),
('Greece', 'GR'),
('Greenland', 'GL'),
('Grenada', 'GD'),
('Guadeloupe', 'GP'),
('Guam', 'GU'),
('Guatemala', 'GT'),
('Guernsey', 'GG'),
('Guinea', 'GN'),
('Guinea-bissau', 'GW'),
('Guyana', 'GY'),
('Haiti', 'HT'),
('Heard Island And Mcdonald Islands', 'HM'),
('Honduras', 'HN'),
('Hong Kong', 'HK'),
('Hungary', 'HU'),
('Iceland', 'IS'),
('India', 'IN'),
('Indonesia', 'ID'),
('Iran, Islamic Republic Of', 'IR'),
('Iraq', 'IQ'),
('Ireland', 'IE'),
('Isle Of Man', 'IM'),
('Israel', 'IL'),
('Italy', 'IT'),
('Jamaica', 'JM'),
('Japan', 'JP'),
('Jersey', 'JE'),
('Jordan', 'JO'),
('Kazakhstan', 'KZ'),
('Kenya', 'KE'),
('Kiribati', 'KI'),
('Korea, Democratic People''s Republic Of', 'KP'),
('Korea, Republic Of', 'KR'),
('Kuwait', 'KW'),
('Kyrgyzstan', 'KG'),
('Lao People''s Democratic Republic', 'LA'),
('Latvia', 'LV'),
('Lebanon', 'LB'),
('Lesotho', 'LS'),
('Liberia', 'LR'),
('Libyan Arab Jamahiriya', 'LY'),
('Liechtenstein', 'LI'),
('Lithuania', 'LT'),
('Luxembourg', 'LU'),
('Macao', 'MO'),
('Macedonia, The Former Yugoslav Republic Of', 'MK'),
('Madagascar', 'MG'),
('Malawi', 'MW'),
('Malaysia', 'MY'),
('Maldives', 'MV'),
('Mali', 'ML'),
('Malta', 'MT'),
('Marshall Islands', 'MH'),
('Martinique', 'MQ'),
('Mauritania', 'MR'),
('Mauritius', 'MU'),
('Mayotte', 'YT'),
('Mexico', 'MX'),
('Micronesia, Federated States Of', 'FM'),
('Moldova', 'MD'),
('Monaco', 'MC'),
('Mongolia', 'MN'),
('Montenegro', 'ME'),
('Montserrat', 'MS'),
('Morocco', 'MA'),
('Mozambique', 'MZ'),
('Myanmar', 'MM'),
('Namibia', 'NA'),
('Nauru', 'NR'),
('Nepal', 'NP'),
('Netherlands', 'NL'),
('Netherlands Antilles', 'AN'),
('New Caledonia', 'NC'),
('New Zealand', 'NZ'),
('Nicaragua', 'NI'),
('Niger', 'NE'),
('Nigeria', 'NG'),
('Niue', 'NU'),
('Norfolk Island', 'NF'),
('Northern Mariana Islands', 'MP'),
('Norway', 'NO'),
('Oman', 'OM'),
('Pakistan', 'PK'),
('Palau', 'PW'),
('Palestinian Territory, Occupied', 'PS'),
('Panama', 'PA'),
('Papua New Guinea', 'PG'),
('Paraguay', 'PY'),
('Peru', 'PE'),
('Philippines', 'PH'),
('Pitcairn', 'PN'),
('Poland', 'PL'),
('Portugal', 'PT'),
('Puerto Rico', 'PR'),
('Qatar', 'QA'),
('REunion', 'RE'),
('Romania', 'RO'),
('Russian Federation', 'RU'),
('Rwanda', 'RW'),
('Saint BarthElemy', 'BL'),
('Saint Helena', 'SH'),
('Saint Kitts And Nevis', 'KN'),
('Saint Lucia', 'LC'),
('Saint Martin', 'MF'),
('Saint Pierre And Miquelon', 'PM'),
('Saint Vincent And The Grenadines', 'VC'),
('Samoa', 'WS'),
('San Marino', 'SM'),
('Sao Tome And Principe', 'ST'),
('Saudi Arabia', 'SA'),
('Senegal', 'SN'),
('Serbia', 'RS'),
('Seychelles', 'SC'),
('Sierra Leone', 'SL'),
('Singapore', 'SG'),
('Slovakia', 'SK'),
('Slovenia', 'SI'),
('Solomon Islands', 'SB'),
('Somalia', 'SO'),
('South Africa', 'ZA'),
('South Georgia And The South Sandwich Islands', 'GS'),
('Spain', 'ES'),
('Sri Lanka', 'LK'),
('Sudan', 'SD'),
('Suriname', 'SR'),
('Svalbard And Jan Mayen', 'SJ'),
('Swaziland', 'SZ'),
('Sweden', 'SE'),
('Switzerland', 'CH'),
('Syrian Arab Republic', 'SY'),
('Taiwan, Province Of China', 'TW'),
('Tajikistan', 'TJ'),
('Tanzania, United Republic Of', 'TZ'),
('Thailand', 'TH'),
('Timor-leste', 'TL'),
('Togo', 'TG'),
('Tokelau', 'TK'),
('Tonga', 'TO'),
('Trinidad And Tobago', 'TT'),
('Tunisia', 'TN'),
('Turkey', 'TR'),
('Turkmenistan', 'TM'),
('Turks And Caicos Islands', 'TC'),
('Tuvalu', 'TV'),
('Uganda', 'UG'),
('Ukraine', 'UA'),
('United Arab Emirates', 'AE'),
('United Kingdom', 'GB'),
('United States', 'US'),
('United States Minor Outlying Islands', 'UM'),
('Uruguay', 'UY'),
('Uzbekistan', 'UZ'),
('Vanuatu', 'VU'),
('Vatican City State', 'VA'),
('Venezuela', 'VE'),
('Viet Nam', 'VN'),
('Virgin Islands, British', 'VG'),
('Virgin Islands, U.s.', 'VI'),
('Wallis And Futuna', 'WF'),
('Western Sahara', 'EH'),
('Yemen', 'YE'),
('Zambia', 'ZM'),
('Zimbabwe', 'ZW');
