-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: 192.168.1.1:3306
-- Generation Time: Feb 05, 2016 at 06:04 PM
-- Server version: 5.5.44-0+deb8u1
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gflclan_browsertf`
--

-- --------------------------------------------------------

--
-- Table structure for table `440-servers`
--

CREATE TABLE IF NOT EXISTS `440-servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `info` varchar(2048) NOT NULL,
  `online` int(1) NOT NULL,
  `lastupdated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4988 ;

-- --------------------------------------------------------

--
-- Table structure for table `730-servers`
--

CREATE TABLE IF NOT EXISTS `730-servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `info` varchar(2048) NOT NULL,
  `online` int(1) NOT NULL,
  `lastupdated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `730-servers`
--

-- --------------------------------------------------------

--
-- Table structure for table `4000-servers`
--

CREATE TABLE IF NOT EXISTS `4000-servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `info` varchar(2048) NOT NULL,
  `online` int(1) NOT NULL,
  `lastupdated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamid` varchar(256) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `steaminfo` varchar(4016) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `accounts`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `message` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `admin_logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `ext` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `dateadded` int(11) NOT NULL,
  `appid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `uid`, `ext`, `title`, `description`, `content`, `dateadded`, `appid`) VALUES
(4, 7, '', '[1-8-16] Website Updates - Being Developed Again!', 'The website is currently being developed again!', 'Hello again, I know it has been a while since I''ve worked on the website. I''ve been very busy with coding other projects (e.g. The GFL Community, TDC, SourcePawn plugins, etc). However, I''ve decided to spend the day on re-coding [b]Browser.TF[/b]. I will also spend tomorrow fixing things up + adding more features.\r\n\r\nChange Log:\r\n- Re-coded the website to use separate pages instead of requesting page content with AJAX.\r\n- Added a home page.\r\n- Added announcements.\r\n- Added group formatting.\r\n- "Add Server" and "Report Server" forms now submit using AJAX.\r\n- Re-coded the server browsers (still fixing some issues).\r\n\r\nIssues so far:\r\n- "Manual" mode is buggy with the full server browser.\r\n- Mobile isn''t complete yet.\r\n\r\nThat is all for tonight.\r\n\r\nPlease spread the word!\r\n\r\nThanks!\r\n\r\n', 1452315507, 0),
(5, 7, '', '[1-9-16] Updates', 'A short list of updates.', 'Hello, here''s a short list of changes I''ve made since yesterday:\n- Mobile is complete.\n- Added a "secondary" NavBar.\n- You can now view server reviews.\n- Edited the "About" page.\n\nI hope everybody enjoys!\n\nThanks.', 1452387083, 0),
(6, 7, '', '[1-16-16] Updates - Improved Browser Speed & New Design', 'Browser speed increased.', 'Hello everybody! Just giving a few updates on what I''ve done with Browser.TF!\r\n\r\nChange Log:\r\n- The "Normal" and "Stock" browsers now use AJAX (through DataTables) to retrieve the server data. Therefore, speed is much more faster. However, the "favorites" option had to be removed from the "Options" column for these two browsers. You can still favorite servers while viewing the server''s page.\r\n- Slightly improved performance on the "Favorites" and "My Servers" browsers.\r\n- Added a background image.\r\n- Changed the colors a bit.\r\n- Improved the secondary NavBar (doesn''t change height when you hover over the items).\r\n- Improved the pagination items on the server tables (removed invisible borders).\r\n\r\nI hope everybody enjoys!\r\n\r\nThanks.', 1452983746, 0);

-- --------------------------------------------------------

--
-- Table structure for table `approved_ratings`
--

CREATE TABLE IF NOT EXISTS `approved_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `keyname` varchar(256) NOT NULL,
  `dname` varchar(256) NOT NULL,
  `rating` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `approved_servers`
--

CREATE TABLE IF NOT EXISTS `approved_servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `appid` int(11) NOT NULL,
  `dateadded` int(11) NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `approved_servers`
--

INSERT INTO `approved_servers` (`id`, `ip`, `port`, `appid`, `dateadded`, `enabled`) VALUES
(1, '66.85.14.11', 27015, 440, 0, 1),
(2, '162.248.88.102', 27015, 440, 0, 1),
(3, '198.24.175.74', 27015, 440, 0, 1),
(4, '198.24.175.78', 27015, 440, 0, 1),
(5, '104.254.239.16', 27015, 440, 0, 0),
(6, '192.223.26.23', 27015, 440, 0, 1),
(7, '192.223.26.40', 27015, 440, 0, 1),
(8, '199.233.235.227', 27015, 440, 1450064363, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cronjobs`
--

CREATE TABLE IF NOT EXISTS `cronjobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `cron` varchar(256) NOT NULL,
  `display` varchar(1024) NOT NULL,
  `description` varchar(2048) NOT NULL,
  `author` varchar(256) NOT NULL,
  `options` varchar(4096) NOT NULL,
  `lastran` int(11) NOT NULL,
  `ranevery` int(11) NOT NULL,
  `timeadded` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cronjobs`
--

INSERT INTO `cronjobs` (`id`, `name`, `cron`, `display`, `description`, `author`, `options`, `lastran`, `ranevery`, `timeadded`, `enabled`) VALUES
(1, 'json_fullbrowser', 'updateJSON', 'JSON - Full Browser', 'Updates the full browser cache.', 'Christian Deacon', '{\r\n	"type": 0,\r\n	"appid": 0\r\n}', 1454713206, 300, 1454699120, 1),
(2, 'updateservers', 'updateServers', 'Update All Servers (DataBase)', 'Updates all the servers in the database using SourceQuery.', 'Christian Deacon', '{\r\n	"appid": 0\r\n}', 1454713206, 345, 1454699120, 1),
(3, 'json_test', 'updateJSON', 'JSON - Test', 'Testing stuff...', 'Christian Deacon', '{\r\n	"type": 0,\r\n	"appid": 730\r\n}', 1454713206, 300, 1454699120, 0),
(4, 'updateservers_test', 'updateServers', 'Update Servers testing.', 'Testing CS:GO servers.', 'Christian Deacon', '{\r\n	"appid": 730\r\n}', 1454713206, 345, 1454699120, 0),
(5, 'updatemasters', 'updateMasters', 'Update Master Servers', 'Updates all of the Master Servers.', 'Christian Deacon', '{\n	"appid": 0\n}', 1454713206, 43200, 1454711293, 1),
(6, 'mastertodatabase', 'masterToDatabase', 'Master Server -> DataBase', 'Inserts servers from the Master Server list to the database.', 'Christian Deacon', '{\r\n    "appid": 0\r\n}', 0, 40000, 1454713211, 0);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `timeadded` int(11) NOT NULL,
  `appid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=144 ;

--
-- Dumping data for table `favorites`
--

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` int(11) NOT NULL,
  `display` varchar(256) NOT NULL,
  `listorder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `appid`, `display`, `listorder`) VALUES
(1, 440, 'Team Fortress 2', 1),
(2, 730, 'Counter-Strike: Global Offensive', 2),
(3, 4000, 'Garry''s Mod', 3);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `dname` varchar(256) NOT NULL,
  `style` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `dname`, `style`) VALUES
(1, 'member', 'Member', 'color: #E3FEFF;'),
(2, 'admin', 'Admin', 'color: #38C93F;'),
(3, 'root', 'Root User', 'color: #D30000;');

-- --------------------------------------------------------

--
-- Table structure for table `loadfiles`
--

CREATE TABLE IF NOT EXISTS `loadfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `file` varchar(1024) NOT NULL,
  `listorder` int(11) NOT NULL,
  `appid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `loadfiles`
--

INSERT INTO `loadfiles` (`id`, `type`, `file`, `listorder`, `appid`) VALUES
(1, 2, 'bootstrap.min.js', 1, 0),
(2, 2, 'custom.js', 2, 0),
(3, 1, 'bootstrap.min.css', 1, 0),
(4, 1, 'custom.css', 2, 1),
(5, 1, 'datatables.custom.css', 3, 1),
(6, 1, 'games/440.css', 1, 440),
(7, 1, 'games/730.css', 1, 730),
(8, 1, 'games/4000.css', 1, 4000);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(256) NOT NULL,
  `key` varchar(256) NOT NULL,
  `message` varchar(2048) NOT NULL,
  `timeadded` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `module`, `key`, `message`, `timeadded`) VALUES
(1, 'crons', 'manualExecute', 'Roy(7) has manually executed the updateservers cron job', 1454706630),
(2, 'crons', 'manualExecute', 'Roy(7) has manually executed the updateservers cron job', 1454706640),
(3, 'crons', 'manualExecute', 'Roy(7) has manually executed the updateservers cron job', 1454706672),
(4, 'crons', 'manualExecute', 'Roy(7) has manually executed the updateservers cron job', 1454706717),
(5, 'crons', 'manualExecute', 'Roy(7) has manually executed the updateservers cron job', 1454706754),
(6, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708207),
(7, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708220),
(8, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708264),
(9, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708706),
(10, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708729),
(11, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708735),
(12, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708737),
(13, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708744),
(14, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708745),
(15, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708751),
(16, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708829),
(17, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708855),
(18, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708921),
(19, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708934),
(20, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708945),
(21, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708946),
(22, 'crons', 'manualExecute', 'Roy(7) has manually executed the json_test cron job', 1454708963),
(23, 'crons', 'manualExecute', 'Roy(7) has manually executed the updateservers_test cron job', 1454709986),
(24, 'crons', 'manualExecute', 'Roy(7) has manually executed the updateservers_test cron job', 1454710008),
(25, 'crons', 'manualExecute', 'Roy(7) has manually executed the updateservers_test cron job', 1454710098),
(26, 'crons', 'manualExecute', 'Roy(7) has manually executed the updatemasters cron job', 1454711381),
(27, 'crons', 'manualExecute', 'Roy(7) has manually executed the updatemasters cron job', 1454711493),
(28, 'crons', 'manualExecute', 'Roy(7) has manually executed the updatemasters cron job', 1454711576),
(29, 'crons', 'manualExecute', 'Roy(7) has manually executed the updatemasters cron job', 1454711657),
(30, 'crons', 'manualExecute', 'Roy(7) has manually executed the updateservers_test cron job', 1454712177),
(31, 'crons', 'updateServers::BadGameRemoval', '69.142.74.51:27015 (4) removed for having the wrong App ID. (730!=240)', 1454712247),
(32, 'crons', 'manualExecute', 'Roy(7) has manually executed the updateservers_test cron job', 1454712247),
(33, 'crons', 'updateServers::BadGameRemoval', '75.118.77.174:27015 (1559) removed for having the wrong App ID. (440!=4000)', 1454712514),
(34, 'crons', 'updateServers::BadGameRemoval', '74.91.123.172:27015 (2076) removed for having the wrong App ID. (440!=730)', 1454712540),
(35, 'crons', 'updateServers::BadGameRemoval', '74.91.121.148:27015 (1326) removed for having the wrong App ID. (440!=4000)', 1454712621),
(36, 'crons', 'updateServers::BadGameRemoval', '185.38.149.73:27215 (3422) removed for having the wrong App ID. (440!=4000)', 1454712841),
(37, 'crons', 'updateServers::BadGameRemoval', '185.38.151.164:27025 (3424) removed for having the wrong App ID. (440!=4000)', 1454712850),
(38, 'crons', 'updateServers::BadGameRemoval', '91.109.15.223:27015 (3701) removed for having the wrong App ID. (440!=240)', 1454712970),
(39, 'crons', 'manualExecute', 'Roy(7) has manually executed the mastertodatabase cron job', 1454713239),
(40, 'crons', 'manualExecute', 'Roy(7) has manually executed the mastertodatabase cron job', 1454713298),
(41, 'crons', 'manualExecute', 'Roy(7) has manually executed the mastertodatabase cron job', 1454713331),
(42, 'crons', 'updateServers::BadGameRemoval', '51.255.5.57:27170 (3808) removed for having the wrong App ID. (440!=730)', 1454713389),
(43, 'crons', 'updateServers::BadGameRemoval', '63.251.20.11:27015 (1253) removed for having the wrong App ID. (440!=4000)', 1454713413),
(44, 'crons', 'masterToDatabase::serverAdded', 'Successfully added 87.98.228.196:27040 to the 730 table!', 1454713416),
(45, 'crons', 'manualExecute', 'Roy(7) has manually executed the mastertodatabase cron job', 1454713416);

-- --------------------------------------------------------

--
-- Table structure for table `manualinfo`
--

CREATE TABLE IF NOT EXISTS `manualinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `players` int(11) NOT NULL,
  `maxplayers` int(11) NOT NULL,
  `hostname` varchar(256) NOT NULL,
  `map` varchar(256) NOT NULL,
  `tags` varchar(1024) NOT NULL,
  `info` varchar(2048) NOT NULL,
  `online` int(1) NOT NULL,
  `appid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21773 ;

--
-- Dumping data for table `manualinfo`
--

-- --------------------------------------------------------

--
-- Table structure for table `navbar`
--

CREATE TABLE IF NOT EXISTS `navbar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display` varchar(1024) NOT NULL,
  `parent` int(11) NOT NULL,
  `newtab` int(11) NOT NULL,
  `url` varchar(256) NOT NULL,
  `listorder` int(11) NOT NULL,
  `permissions` varchar(2048) NOT NULL,
  `appid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `navbar`
--

INSERT INTO `navbar` (`id`, `display`, `parent`, `newtab`, `url`, `listorder`, `permissions`, `appid`) VALUES
(1, 'Home', 0, 0, 'index.php', 1, '', 0),
(2, 'Browser', 0, 0, 'pages/browser/index.php', 2, '', 0),
(3, 'Favorites Browser', 2, 0, 'pages/browser/favorites.php', 1, '{ "0": "1", "1": "2", "2": "3" }', 0),
(4, 'Stock Browser', 2, 0, 'pages/browser/stock.php', 2, '', 440),
(5, 'My Servers', 2, 0, 'pages/browser/myservers.php', 3, '{ "0": "1", "1": "2", "2": "3" }', 0),
(6, 'Admin', 0, 0, 'pages/admin/index.php', 7, '{ "0": "2", "1": "3" }', 0),
(7, 'Add Stock Server', 10, 0, 'pages/servers/addserver.php', 1, '', 440),
(8, 'Report Stock Server', 10, 0, 'pages/servers/reportserver.php', 2, '', 440),
(9, 'About', 0, 0, 'pages/about/index.php', 8, '', 0),
(10, 'Servers', 0, 0, '#', 6, '', 0),
(12, 'Add Server', 10, 0, 'pages/servers/addservertolist.php', 3, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `owned_servers`
--

CREATE TABLE IF NOT EXISTS `owned_servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `appid` int(11) NOT NULL,
  `lastupdated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `owned_servers`
--

INSERT INTO `owned_servers` (`id`, `ip`, `port`, `aid`, `appid`, `lastupdated`) VALUES
(1, '66.85.14.11', 27015, 7, 440, 1454538302),
(4, '192.223.26.40', 27015, 7, 440, 1454538302),
(5, '74.91.124.2', 27015, 7, 440, 1454538302),
(6, '69.142.74.51', 27015, 7, 730, 0);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(256) NOT NULL,
  `permissions` varchar(2048) NOT NULL,
  `notes` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `key`, `permissions`, `notes`) VALUES
(1, 'navitem-3', '{ "0": "1", "1": "2", "2": "3" }', 'Favorites Nav Item.'),
(2, 'navitem-5', '{ "0": "1", "1": "2", "2": "3" }', 'My Servers Nav Item.'),
(3, 'navitem-6', '{ "0": "2", "1": "3" }', 'Admin Panel Nav Item.'),
(4, 'AP-Servers', '{ "0": "2", "1": "3" }', 'Admin Panel - Servers'),
(5, 'AP-ServerRequests', '{ "0": "2", "1": "3" }', 'Admin Panel - Server Requests'),
(6, 'AP-ServerReports', '{ "0": "2", "1": "3" }', 'Admin Panel - Server Reports'),
(7, 'AP-Users', '{ "0": "3" }', 'Admin Panel - Users'),
(8, 'AP-Announcements', '{ "0": "2", "1": "3" }', 'Admin Panel - Announcements.');

-- --------------------------------------------------------

--
-- Table structure for table `population`
--

CREATE TABLE IF NOT EXISTS `population` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `population` int(11) NOT NULL,
  `populationmax` int(11) NOT NULL,
  `map` varchar(256) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83380 ;

--
-- Dumping data for table `population`
--



-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` varchar(2048) NOT NULL,
  `timeadded` int(11) NOT NULL,
  `appid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `aid`, `ip`, `port`, `rating`, `review`, `timeadded`, `appid`) VALUES
(6, 7, '192.223.26.40', 27015, 9, 'Great server!', 1445189654, 0),
(7, 7, '207.173.67.28', 27019, 4, 'The server performance wasn''t acceptable in my opinion.\nI was only receiving around 40-60 updates when the server was around 25-30 players. This was due to the fact that the "net_splitpacket_maxrate" was set to default (bad for a large server). Their "sv_maxrate" was also set fairly low (60000), but somewhat acceptable. The server FPS was all over the place. Therefore, I feel they need to upgrade their hard-ware as well or load-balance their servers.', 1445650141, 0),
(8, 7, '74.91.124.34', 27015, 9, 'Great performance. No Pay-To-Win advantages. Fair game-play.', 1445650944, 0),
(9, 3, '74.91.119.154', 27015, 5, 'Test', 1445726462, 0),
(10, 7, '74.91.124.2', 27015, 10, 'Excellent performance and great custom game-play!', 1445780168, 0),
(11, 7, '209.126.106.164', 27015, 8, 'Pros:\n- Great performance (server FPS, rates, etc).\n- No MOTD Video Ads.\n\nCons:\n- When I was playing on the server, I was being spammed with gifts. I am 95% sure this was a server-specific feature and it was quite annoying.\n\nSuggestions:\n- Raise "net_splitpacket_maxrate" to get rid of all the choke on the server.\n\nOverall, other then the random gift spamming, this is a great server!\n\nThanks.', 1452997001, 0),
(12, 7, '104.255.67.178', 27015, 2, 'Pros:\n- Okay rates I guess?\n\nCons:\n- Runs MOTD Video Ads.\n- One of the worse performing TF2 servers I''ve seen (terrible server FPS).\n\nSuggestions:\n- Switch host or upgrade your box. I would recommend using NFOServers.com.\n\nOverall, I seriously don''t know how this server is 4th on GameTracker. The server isn''t playable due to the lag and I''m not sure how others can stay on the server. \n\nThanks.\n', 1452997457, 0),
(13, 7, '23.235.225.13', 27015, 10, 'Pros:\n- Great performance.\n- Not MOTD Video Ads.\n- Great community.\n\nCons:\n- None so far.\n\nSuggestions:\n- Raise "net_splitpacket_maxrate" slightly higher to get rid of the choke. That''s not a big deal though.\n\nOverall, a great server.', 1453053564, 0),
(14, 7, '216.52.148.47', 27015, 10, 'Great server!!!', 1453084593, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `dateadded` int(11) NOT NULL,
  `reason` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `aid`, `ip`, `port`, `dateadded`, `reason`) VALUES
(1, 7, '7', 0, 1444612992, 'Just a test.'),
(2, 7, '1', 0, 1444613241, 'This server is running custom maps.\r\n\r\nNormally, I wouldn''t mind, but this server browser is specifically made for stock servers, correct? Therefore, please remove this server.\r\n\r\nThanks.'),
(3, 7, '192.156.123.12', 27018, 1445187453, 'Test report.'),
(4, 7, 'tf2.gflclan.com', 27016, 1452308967, 'THEY CHEATEN!');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `ip` varchar(256) NOT NULL,
  `port` varchar(256) NOT NULL,
  `location` varchar(1024) NOT NULL,
  `email` varchar(256) NOT NULL,
  `other` varchar(2048) NOT NULL,
  `timeadded` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `notes` varchar(1024) NOT NULL,
  `appid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `aid`, `ip`, `port`, `location`, `email`, `other`, `timeadded`, `status`, `notes`, `appid`) VALUES
(3, 1, '192.123.512.123', '27015', 'US West', 'christiand@thedevelopingcommunity.com', 'I love this website!', 1444496786, 1, 'Plzzzzzz', 0),
(4, 1, '74.91.119.75', '27015', 'US East', 'gamemann@gflclan.com', '', 1444502797, 2, 'Custom Server.', 0),
(5, 3, 'orange.gflclan.com', '27015', 'Chicago', 'Infantry@gflclan.com', '', 1444596783, 2, 'No custom servers allowed.', 0),
(6, 7, '111.111.111.11', '27035', 'US East', 'gamemann@gflclan.com', '', 1444609761, 2, 'Not a valid IP?', 0),
(7, 7, '192.168.1.98', '27015', 'Mullica Hill, New Jersey', 'gamemann@gflclan.com', '', 1444609942, 2, 'This is your LAN IP.', 0),
(8, 7, '216.52.148.47', '27015', 'US East', 'christiand@thedevelopingcommunity.com', '', 1444610336, 2, 'This is a CS:GO server...', 0),
(9, 7, 'please', '223', '223', 'gamemann@gflclan.com', '', 1444610471, 2, 'Sigh...', 0),
(10, 30, '199.233.235.227', '27015', 'Atlanta, Georgia (US East)', 'nicole@probablyaserver.com', 'Reddit', 1450054503, 1, 'By Nicole. Need to test server.', 0),
(11, 7, 'gflclan.com', '28123', 'US East', 'GFLClan', 'nothingnew', 1452308254, 2, '', 0),
(13, 7, 'tf2.gflclan.com', '27016', 'US West', 'christiand@thedevelopingcommunity.com', 'I wanted something new.', 1452308457, 1, 'Please!', 0);

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(256) NOT NULL,
  `port` int(11) NOT NULL,
  `region` int(11) NOT NULL,
  `region_descriptive` varchar(256) NOT NULL,
  `lastupdated` int(11) NOT NULL,
  `dateadded` int(11) NOT NULL,
  `enabled` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `servers`
--

INSERT INTO `servers` (`id`, `ip`, `port`, `region`, `region_descriptive`, `lastupdated`, `dateadded`, `enabled`) VALUES
(1, '66.85.14.11', 27015, 1, 'US East', 1445206202, 0, 1),
(2, '162.248.88.102', 27015, 1, 'US East', 1445206202, 0, 1),
(3, '198.24.175.74', 27015, 1, 'US East', 1445206202, 0, 1),
(4, '198.24.175.78', 27015, 1, 'US East', 1445206202, 0, 1),
(5, '104.254.239.16', 27015, 1, 'US East', 1445206203, 0, 0),
(6, '192.223.26.23', 27015, 1, 'US East', 1445206203, 0, 1),
(7, '192.223.26.40', 27015, 1, 'US East', 1445206203, 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
