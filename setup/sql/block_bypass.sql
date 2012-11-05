-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2012 at 08:31 PM
-- Server version: 5.1.43
-- PHP Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `reg8log`
--

-- --------------------------------------------------------

--
-- Table structure for table `block_bypass`
--

CREATE TABLE IF NOT EXISTS `block_bypass` (
  `auto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `username_exists` tinyint(1) NOT NULL,
  `num_requests` tinyint(4) unsigned DEFAULT NULL,
  `emails_sent` tinyint(3) unsigned NOT NULL,
  `key` varchar(22) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `last_attempt` int(11) unsigned NOT NULL COMMENT 'last incorrect login attempt (copied from the account_incorrect_logins table)',
  `incorrect_logins` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`auto`),
  UNIQUE KEY `username` (`username`),
  KEY `last_attempt` (`last_attempt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
