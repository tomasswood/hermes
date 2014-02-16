-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2014 at 06:12 AM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `flindle_gus`
--

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE IF NOT EXISTS `card` (
  `cc_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cc_lastfour` smallint(4) NOT NULL,
  `cc_type` varchar(20) NOT NULL,
  UNIQUE KEY `cc_id` (`cc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `card`
--

INSERT INTO `card` (`cc_id`, `cc_lastfour`, `cc_type`) VALUES
(1, 4242, 'Visa'),
(2, 4444, 'MasterCard'),
(3, 4242, 'Visa'),
(4, 1881, 'Visa'),
(5, 4444, 'MasterCard'),
(6, 4242, 'Visa'),
(7, 4242, 'Visa');

-- --------------------------------------------------------

--
-- Table structure for table `cardlink`
--

CREATE TABLE IF NOT EXISTS `cardlink` (
  `cl_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cl_cardid` bigint(20) DEFAULT NULL,
  `cl_userid` bigint(20) DEFAULT NULL,
  `cl_pegasusid` bigint(20) DEFAULT NULL,
  `cl_customerid` varchar(255) NOT NULL,
  UNIQUE KEY `cl_id` (`cl_id`),
  KEY `cl_cardid` (`cl_cardid`,`cl_userid`,`cl_pegasusid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `cardlink`
--

INSERT INTO `cardlink` (`cl_id`, `cl_cardid`, `cl_userid`, `cl_pegasusid`, `cl_customerid`) VALUES
(1, NULL, 15, NULL, 'cus_3LDKRq1T5zFwJm'),
(2, 5, 2, NULL, 'cus_3LDu7j8rljh7LU'),
(3, 6, NULL, 1, 'cus_3LFCvl653o3EUy'),
(4, NULL, NULL, 10, 'cus_3LISeFsAVc2t0Z'),
(5, 7, NULL, 11, 'cus_3LIYePmaCXlWgM'),
(6, NULL, NULL, 12, 'cus_3LzlGMD1uCqPKr'),
(7, NULL, NULL, 13, 'cus_3LzrTuEnHfaMj9'),
(8, NULL, NULL, 13, 'cus_3Pll6YHu96040s'),
(9, NULL, 16, NULL, 'cus_3PllYiDL61CHI5'),
(10, NULL, NULL, 14, 'cus_3PlsD6Oh4qKIF2'),
(11, NULL, 17, NULL, 'cus_3PlsZDJ2Y88upi'),
(12, NULL, NULL, 15, 'cus_3PlyJs5H6AnwGi'),
(13, NULL, 18, NULL, 'cus_3Ply8i37UfIDn0'),
(14, NULL, 19, NULL, 'cus_3PmPmgsOoUhTAJ'),
(15, NULL, NULL, 16, 'cus_3PmUwMWptc3Aa4'),
(16, NULL, 20, NULL, 'cus_3PmUl8thSMRqIK'),
(17, NULL, NULL, 17, 'cus_3PmdFJxMcO8YzD'),
(18, NULL, 21, NULL, 'cus_3PmdKAekzBqonk'),
(19, NULL, NULL, 18, 'cus_3Q8hLdY7MDj53x');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `u_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `u_facebookid` int(20) unsigned DEFAULT NULL,
  `u_firstname` varchar(25) NOT NULL,
  `u_lastname` varchar(50) NOT NULL,
  `u_username` varchar(25) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `u_email` varchar(50) NOT NULL,
  `u_status` enum('Active','Inactive','Expired','Banned') NOT NULL DEFAULT 'Inactive',
  `u_authkey` varchar(10) NOT NULL,
  `u_mauth` varchar(10) DEFAULT NULL,
  `u_mauthexpiry` timestamp NULL DEFAULT NULL,
  `u_cardlinkid` bigint(20) DEFAULT NULL,
  `u_lastlogin` timestamp NULL DEFAULT NULL,
  `u_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_userlogoid` bigint(20) unsigned DEFAULT '1',
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `u_username` (`u_username`),
  UNIQUE KEY `u_id` (`u_id`),
  KEY `u_email` (`u_email`),
  KEY `u_logoid` (`u_userlogoid`),
  KEY `u_facebookid` (`u_facebookid`),
  KEY `u_customercardid` (`u_cardlinkid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='All of the user accounts' AUTO_INCREMENT=22 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `u_facebookid`, `u_firstname`, `u_lastname`, `u_username`, `u_password`, `u_email`, `u_status`, `u_authkey`, `u_mauth`, `u_mauthexpiry`, `u_cardlinkid`, `u_lastlogin`, `u_registered`, `u_userlogoid`) VALUES
(1, NULL, 'Thomas', 'Wood', 'thomas', '$2y$10$UuZXchxy0.qsvbZUkhGqFO4K4t32Nj/HxioHH54JB1.j5cjzVBXPq', 'thomas@hotmail.com.au', 'Active', 'e0503cf102', NULL, NULL, 1, '2013-12-22 01:31:55', '0000-00-00 00:00:00', 1),
(2, 746232461, 'Tom', 'Hanlon', 'tomhan', '$2y$10$kgHLToK6VVlUY7YVrISNaen8letKKFM1fvJtjiHezLtXVezMRrQte', 'tomasswood@hotmail.com', 'Active', '41348d9c11', NULL, NULL, 2, '2014-02-02 04:50:14', '0000-00-00 00:00:00', 11),
(3, NULL, 'Thom', 'Wheeler', 'thomw', '$2y$10$EtgRIG5PL38uK1WSdfTQKOd2eqWSmU/C.jXO.GNTgQKpMY0f5/ULy', 'wheeler.99@hotmail.com', 'Active', '83791fbfba', NULL, NULL, NULL, '2013-12-22 01:31:55', '0000-00-00 00:00:00', 1),
(4, NULL, 'Allan', 'Smith', 'allan', '$2y$10$8vfFjo/cdq//w3fsnoHkour0EU7kUaOJgtLyS446rBBKld.oXUmoa', 'just.strike.alight@gmail.com', 'Active', 'bae11f83f8', NULL, NULL, NULL, '2013-12-22 01:31:55', '0000-00-00 00:00:00', 1),
(5, NULL, 'Sally', 'Reid', 'sreid', '$2y$10$6MOMOsSuv4351TpbqDLHwOdLyXeCaSooDh9MUZatWRMm5PfLAqWVa', 'sally.reid@mbs.com', 'Active', '01d8801561', NULL, NULL, NULL, '2013-12-22 01:31:55', '0000-00-00 00:00:00', 1),
(6, NULL, 'Claire', 'Smart', 'csmart', '$2y$10$wvvjCRNnuTCatOvW1z5k5.MCFNcSV/Fx55ofTZJC64juWWI.N9qa.', 'claire.smart@mbs.com', 'Active', '1232ae10c0', NULL, NULL, NULL, '2013-12-22 01:31:55', '0000-00-00 00:00:00', 1),
(7, NULL, 'Brad', 'Pitt', 'BradPitt', '$2y$10$dpJ9MHysAaMENsUMhHbB9ei5a35qz//lOyGloBGnl0dwNKryQQ96q', 'bradpitt@gmail.com', 'Active', '9ee5de5a4a', NULL, NULL, NULL, '2013-12-22 01:31:55', '0000-00-00 00:00:00', 1),
(8, NULL, 'Allan', 'Smith', 'allanstest', '$2y$10$nz/ntr.mRSuH5MpkNQPMPOs.G549jEzXvBBADRWs/.u8CDS1iXAXK', 'allan.biohazard@gmail.com', 'Active', '123f5ca1ac', NULL, NULL, NULL, '2013-12-22 01:31:55', '0000-00-00 00:00:00', 1),
(9, NULL, 'test', 'tester', 'test', '$2y$10$UfzB6kJky/Cf8nBlxD3Fk.kK26ho30TOQI5HI3QaxsU/rr2feonKC', 'tomasswoood@hotmail.com', 'Active', 'a6db3409be', NULL, NULL, NULL, '2014-01-10 10:52:54', '2013-11-13 00:55:48', 1),
(10, NULL, 'elke', 'henderson', 'elkeh', '$2y$10$/yswxopzvrFb0YwqRpyw8u3zotZLP/UCvGWmg1k7QXQBK77iHsIYy', 'elke.henderson@hotmail.com', 'Active', '19d68f6ca8', NULL, NULL, NULL, '2013-12-22 01:31:55', '2013-11-24 10:53:58', 1),
(14, NULL, 'Thomas', 'Wood', 'tomasswood', '$2y$10$1xGo3JQ3p9DHw8IsfMaDXe5rcFIX6YOGFinjnqfCbftYwYYTy7uhO', 'thomas.wood@flindle.com', 'Inactive', 'f84a9fe92a', NULL, NULL, NULL, NULL, '2014-01-10 10:32:50', 1),
(15, 0, 'thomas', 'wood', 'thomaswood', '$2y$10$8vIN5TbeP0CnbO3bt.q4aOqRn6bVmrRc.ztni/Cr50wWd3zFbB.9S', 'thomas@hotmales.com', 'Inactive', '43bb733c1b', NULL, NULL, 1, NULL, '2014-01-20 01:31:12', 1),
(21, NULL, '', '', 'foodelke', '$2y$10$egpMYCC3MDdb7JFXxE.H4OIw1w8NZxg0eb/CA1r.tRhs9Pis.SaHu', 'foodelke@food.com', 'Inactive', 'a5d4c8d68d', NULL, NULL, 18, NULL, '2014-02-01 06:16:03', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
