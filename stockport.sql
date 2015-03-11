-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2015 at 06:16 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `stockport`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `checkPort`(`qty` INT, `cost` DECIMAL(15,2), `customer_id` INT, `sym_id` VARCHAR(30)) RETURNS tinyint(1)
BEGIN
      DECLARE numShare INT DEFAULT 0;
      DECLARE result BOOL DEFAULT FALSE;
      
      SELECT shares INTO numShare
      FROM portfolio
      WHERE symbol_id = sym_id AND user_id = customer_id
      LIMIT 1;
      
      IF numShare = 0 THEN
        DELETE FROM portfolio WHERE user_id = customer_id AND symbol_id = sym_id;
        SET result = FALSE;
      ELSEIF numShare >= qty THEN 
        UPDATE users SET balance = balance + cost WHERE pri = customer_id;
        UPDATE portfolio SET shares = shares - qty, total_value = total_value - cost WHERE user_id = customer_id AND symbol_id = sym_id;
        SET result = TRUE;      
      ELSEIF numShare IS NULL THEN SET result = FALSE;
      ELSE SET result = FALSE;
      END IF;
      RETURN result;
    
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE IF NOT EXISTS `portfolio` (
`pri` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(9) NOT NULL,
  `symbol_id` varchar(50) NOT NULL,
  `shares` mediumint(9) NOT NULL,
  `total_value` decimal(15,2) NOT NULL,
  `duplicate` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`pri` mediumint(8) unsigned NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `registration_date` datetime NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '10000.00',
  `duplicate` mediumint(9) NOT NULL,
  `reset_key` varchar(40) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `portfolio`
--
ALTER TABLE `portfolio`
 ADD PRIMARY KEY (`pri`), ADD UNIQUE KEY `user_id` (`user_id`,`symbol_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`pri`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `portfolio`
--
ALTER TABLE `portfolio`
MODIFY `pri` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `pri` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
