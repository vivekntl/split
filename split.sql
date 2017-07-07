-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 07, 2017 at 03:56 PM
-- Server version: 10.1.22-MariaDB
-- PHP Version: 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `split`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(6) NOT NULL,
  `brand_name` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deals`
--

CREATE TABLE `deals` (
  `deal_id` int(11) NOT NULL,
  `store_id` int(6) NOT NULL,
  `deal_description` varchar(200) NOT NULL,
  `type_id` int(2) NOT NULL,
  `productX_id` varchar(2) NOT NULL,
  `brand_id` int(6) NOT NULL,
  `ProductY_id` varchar(2) NOT NULL,
  `start_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `X1` int(10) NOT NULL,
  `Y1` int(10) NOT NULL,
  `X2` int(10) NOT NULL,
  `Y2` int(10) NOT NULL,
  `X3` int(10) NOT NULL,
  `Y3` int(10) NOT NULL,
  `count_daily` int(10) NOT NULL,
  `count_total` int(10) NOT NULL,
  `num_ppl_match` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deals`
--

INSERT INTO `deals` (`deal_id`, `store_id`, `deal_description`, `type_id`, `productX_id`, `brand_id`, `ProductY_id`, `start_timestamp`, `end_timestamp`, `X1`, `Y1`, `X2`, `Y2`, `X3`, `Y3`, `count_daily`, `count_total`, `num_ppl_match`) VALUES
(1, 1, 'dfasdf', 1, '1', 1, '1', '2017-07-07 08:50:23', '0000-00-00 00:00:00', 2, 11, 3, 20, 4, 50, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `deal_history`
--

CREATE TABLE `deal_history` (
  `deal_id` int(6) NOT NULL,
  `store_id` int(6) NOT NULL,
  `deal_description` varchar(200) NOT NULL,
  `type_id` enum('1','2','3') NOT NULL,
  `productX_id` varchar(2) NOT NULL,
  `brand_id` varchar(6) NOT NULL,
  `ProductY_id` varchar(2) NOT NULL,
  `start_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `X1` int(10) NOT NULL,
  `Y1` int(10) NOT NULL,
  `X2` int(10) NOT NULL,
  `Y2` int(10) NOT NULL,
  `X3` int(10) NOT NULL,
  `Y3` int(10) NOT NULL,
  `count_daily` int(10) NOT NULL,
  `count_total` int(10) NOT NULL,
  `num_ppl_match` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deal_type`
--

CREATE TABLE `deal_type` (
  `type_id` int(2) NOT NULL,
  `type_description` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE `product_type` (
  `product_id` int(2) NOT NULL,
  `product_name` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` int(6) NOT NULL,
  `store_name` varchar(250) NOT NULL,
  `store_address` varchar(250) NOT NULL,
  `store_contact` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `success_matchups`
--

CREATE TABLE `success_matchups` (
  `match_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `chat_id` varchar(5) NOT NULL,
  `code` varchar(10) NOT NULL,
  `num_matches` int(2) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user1_units` int(10) NOT NULL,
  `user1_cost_price` int(10) NOT NULL,
  `user1_buy_price` int(10) NOT NULL,
  `user1_savings` int(10) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `user2_units` int(10) NOT NULL,
  `user2_cost_price` int(10) NOT NULL,
  `user2_buy_price` int(10) NOT NULL,
  `user2_savings` int(10) NOT NULL,
  `user3_id` int(11) NOT NULL,
  `user3_units` int(10) NOT NULL,
  `user3_cost_price` int(10) NOT NULL,
  `user3_buy_price` int(10) NOT NULL,
  `user3_savings` int(10) NOT NULL,
  `matchup_status` enum('MATCHED','BILLED') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `success_matchups`
--

INSERT INTO `success_matchups` (`match_id`, `deal_id`, `chat_id`, `code`, `num_matches`, `user1_id`, `user1_units`, `user1_cost_price`, `user1_buy_price`, `user1_savings`, `user2_id`, `user2_units`, `user2_cost_price`, `user2_buy_price`, `user2_savings`, `user3_id`, `user3_units`, `user3_cost_price`, `user3_buy_price`, `user3_savings`, `matchup_status`) VALUES
(1, 1, '', '3825', 3, 4, 0, 0, 0, 0, 6, 0, 0, 0, 0, 8, 0, 0, 0, 0, 'MATCHED'),
(2, 2, '', '0497', 3, 2, 0, 0, 0, 0, 5, 0, 0, 0, 0, 8, 0, 0, 0, 0, 'MATCHED'),
(3, 1, '', '1543', 2, 12, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'MATCHED');

-- --------------------------------------------------------

--
-- Table structure for table `success_matchups_history`
--

CREATE TABLE `success_matchups_history` (
  `match_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `chat_id` varchar(5) NOT NULL,
  `code` varchar(10) NOT NULL,
  `num_matches` int(2) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user1_units` int(10) NOT NULL,
  `user1_cost_price` int(10) NOT NULL,
  `user1_buy_price` int(10) NOT NULL,
  `user1_savings` int(10) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `user2_units` int(10) NOT NULL,
  `user2_cost_price` int(10) NOT NULL,
  `user2_buy_price` int(10) NOT NULL,
  `user2_savings` int(10) NOT NULL,
  `user3_id` int(11) NOT NULL,
  `user3_units` int(10) NOT NULL,
  `user3_cost_price` int(10) NOT NULL,
  `user3_buy_price` int(10) NOT NULL,
  `user3_savings` int(10) NOT NULL,
  `matchup_status` enum('MATCHED','BILLED') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_detail`
--

CREATE TABLE `user_detail` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_dob` date NOT NULL,
  `user_gender` enum('Male','Female','Other') NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_contact` varchar(15) NOT NULL,
  `user_status` enum('Verified','Not verified') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_matchup_history`
--

CREATE TABLE `user_matchup_history` (
  `user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_pending_matches`
--

CREATE TABLE `user_pending_matches` (
  `deal_id` varchar(11) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `X` varchar(1000) NOT NULL,
  `match_status` enum('UNMATCHED','MATCHED','BILLED') NOT NULL,
  `no_of_matchers` int(1) NOT NULL,
  `user_id1` int(11) NOT NULL,
  `user_id2` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `working_matches`
--

CREATE TABLE `working_matches` (
  `deal_id` int(11) NOT NULL,
  `interests` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `deals`
--
ALTER TABLE `deals`
  ADD PRIMARY KEY (`deal_id`);

--
-- Indexes for table `deal_history`
--
ALTER TABLE `deal_history`
  ADD PRIMARY KEY (`deal_id`);

--
-- Indexes for table `deal_type`
--
ALTER TABLE `deal_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `product_type`
--
ALTER TABLE `product_type`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `success_matchups`
--
ALTER TABLE `success_matchups`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `success_matchups_history`
--
ALTER TABLE `success_matchups_history`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `user_detail`
--
ALTER TABLE `user_detail`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `working_matches`
--
ALTER TABLE `working_matches`
  ADD PRIMARY KEY (`deal_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deals`
--
ALTER TABLE `deals`
  MODIFY `deal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `deal_history`
--
ALTER TABLE `deal_history`
  MODIFY `deal_id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `success_matchups`
--
ALTER TABLE `success_matchups`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_detail`
--
ALTER TABLE `user_detail`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
