-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2018 at 02:39 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vbags`
--

-- --------------------------------------------------------

--
-- Table structure for table `addressdetails`
--

CREATE TABLE `addressdetails` (
  `addressId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `addressLine1` varchar(500) NOT NULL,
  `addressLine2` varchar(500) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` int(11) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `addressdetails`
--

INSERT INTO `addressdetails` (`addressId`, `userId`, `addressLine1`, `addressLine2`, `city`, `state`, `pincode`, `country`) VALUES
(6, 2, 'D-11,Narayan Smruti', 'Gandhi Nagar', 'Dombivli', 'Maharashtra', 421201, 'India');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartId` int(11) NOT NULL,
  `userId` varchar(500) NOT NULL,
  `productId` varchar(500) NOT NULL,
  `qty` varchar(500) NOT NULL,
  `productPrice` varchar(100) NOT NULL,
  `deliveryCharges` varchar(100) NOT NULL,
  `totalPrice` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `categoryName` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryId`, `categoryName`) VALUES
(1, 'Pursr'),
(2, 'Bags'),
(3, 'Saari cover'),
(4, 'Hanger');

-- --------------------------------------------------------

--
-- Table structure for table `firebasetokensdetails`
--

CREATE TABLE `firebasetokensdetails` (
  `firebaseTokensId` int(11) NOT NULL,
  `deviceId` int(11) NOT NULL,
  `token` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orderdeliverychages`
--

CREATE TABLE `orderdeliverychages` (
  `orderDeliveryChagesId` int(11) NOT NULL,
  `deliveryCharges` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `orderId` int(11) NOT NULL,
  `orderBulkId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `productPrice` varchar(100) NOT NULL,
  `orderDeliveryChages` varchar(50) NOT NULL,
  `priceWithDelCharges` varchar(50) NOT NULL,
  `luckyDrawPrice` int(11) NOT NULL DEFAULT '0',
  `totalPrice` decimal(10,0) NOT NULL,
  `shippingAddressId` int(11) NOT NULL,
  `orderStatusId` int(11) NOT NULL DEFAULT '1',
  `entryDate` date NOT NULL,
  `isOrderCancel` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`orderId`, `orderBulkId`, `userId`, `productId`, `qty`, `productPrice`, `orderDeliveryChages`, `priceWithDelCharges`, `luckyDrawPrice`, `totalPrice`, `shippingAddressId`, `orderStatusId`, `entryDate`, `isOrderCancel`) VALUES
(1, 1, 1, 1, 1, '150', '55', '205', 0, '205', 1, 2, '2018-07-06', 0),
(2, 2, 2, 4, 4, '250', '55', '1055', 0, '1065', 6, 1, '2018-07-12', 0),
(3, 2, 2, 15, 3, '350', '55', '1105', 0, '1065', 6, 1, '2018-07-12', 0),
(4, 4, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(5, 4, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(6, 6, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(7, 6, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(8, 8, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(9, 8, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(10, 10, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(11, 10, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(12, 12, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(13, 12, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(14, 14, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(15, 14, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(16, 16, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(17, 16, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(18, 18, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(19, 18, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(20, 20, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(21, 20, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(22, 22, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(23, 22, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(24, 24, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(25, 24, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(26, 26, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(27, 26, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(28, 28, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(29, 28, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(30, 30, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(31, 30, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(32, 32, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(33, 32, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(34, 34, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(35, 34, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0),
(36, 36, 2, 11, 1, '20', '55', '75', 0, '1775', 6, 1, '2018-07-13', 0),
(37, 36, 2, 15, 5, '350', '55', '1750', 0, '1775', 6, 1, '2018-07-13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orderstatus`
--

CREATE TABLE `orderstatus` (
  `orderStatusId` int(11) NOT NULL,
  `statusName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderstatus`
--

INSERT INTO `orderstatus` (`orderStatusId`, `statusName`) VALUES
(1, 'Ordered'),
(2, 'Confirmed'),
(3, 'Packed'),
(4, 'Shipped'),
(5, 'Delivered'),
(6, 'Canceled');

-- --------------------------------------------------------

--
-- Table structure for table `paymentdetails`
--

CREATE TABLE `paymentdetails` (
  `paymentId` int(11) NOT NULL,
  `orderBulkId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `paymentType` varchar(100) NOT NULL,
  `razorpayPaymentID` varchar(500) NOT NULL,
  `finalPaymentAmount` int(11) NOT NULL,
  `entryDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `paymentdetails`
--

INSERT INTO `paymentdetails` (`paymentId`, `orderBulkId`, `userId`, `paymentType`, `razorpayPaymentID`, `finalPaymentAmount`, `entryDate`) VALUES
(1, 1, 2, 'Offline', '', 205, '2018-06-23 12:21:10'),
(2, 2, 2, 'Offline', '', 1555, '2018-06-23 14:27:49'),
(3, 5, 2, 'Offline', '', 1055, '2018-06-23 15:41:49'),
(4, 8, 2, 'Offline', '', 1055, '2018-06-23 15:44:22'),
(5, 11, 2, 'Offline', '', 1055, '2018-06-23 15:46:26'),
(6, 14, 2, 'Offline', '', 1055, '2018-06-23 15:47:39'),
(7, 17, 2, 'Offline', '', 1055, '2018-06-23 15:52:58'),
(8, 20, 2, 'Offline', '', 1055, '2018-06-23 15:53:50'),
(9, 23, 2, 'Offline', '', 1055, '2018-06-23 16:00:00'),
(10, 26, 2, 'Offline', '', 1055, '2018-06-23 16:12:49'),
(11, 29, 2, 'Offline', '', 1055, '2018-06-23 16:13:08'),
(12, 32, 2, 'Offline', '', 1055, '2018-06-23 16:14:23'),
(13, 35, 2, 'Offline', '', 1055, '2018-06-23 16:18:49'),
(14, 38, 2, 'Offline', '', 1055, '2018-06-23 16:19:36'),
(15, 1, 2, 'Offline', '', 205, '2018-06-23 16:20:39'),
(16, 2, 2, 'Offline', '', 205, '2018-06-23 16:21:26'),
(17, 3, 2, 'Offline', '', 305, '2018-06-23 16:22:24'),
(18, 4, 2, 'Offline', '', 305, '2018-06-23 16:28:11'),
(19, 5, 2, 'Offline', '', 305, '2018-06-23 16:29:24'),
(20, 6, 2, 'Offline', '', 305, '2018-06-23 16:30:37'),
(21, 7, 2, 'Offline', '', 305, '2018-06-23 16:33:03'),
(22, 8, 2, 'Offline', '', 305, '2018-06-23 16:34:01'),
(23, 2, 2, 'Offline', '', 1065, '2018-07-12 18:42:26'),
(24, 4, 2, 'Offline', '', 1775, '2018-07-13 09:57:38'),
(25, 6, 2, 'Offline', '', 1775, '2018-07-13 10:03:02'),
(26, 8, 2, 'Offline', '', 1775, '2018-07-13 10:04:00'),
(27, 10, 2, 'Offline', '', 1775, '2018-07-13 10:04:42'),
(28, 12, 2, 'Offline', '', 1775, '2018-07-13 10:07:38'),
(29, 14, 2, 'Offline', '', 1775, '2018-07-13 10:09:25'),
(30, 16, 2, 'Offline', '', 1775, '2018-07-13 10:10:50'),
(31, 18, 2, 'Offline', '', 1775, '2018-07-13 10:12:33'),
(32, 20, 2, 'Offline', '', 1775, '2018-07-13 10:33:55'),
(33, 22, 2, 'Offline', '', 1775, '2018-07-13 10:34:42'),
(34, 24, 2, 'Offline', '', 1775, '2018-07-13 10:35:07'),
(35, 26, 2, 'Offline', '', 1775, '2018-07-13 10:37:14'),
(36, 28, 2, 'Offline', '', 1775, '2018-07-13 10:38:24'),
(37, 30, 2, 'Offline', '', 1775, '2018-07-13 10:38:57'),
(38, 32, 2, 'Offline', '', 1775, '2018-07-13 10:50:33'),
(39, 34, 2, 'Offline', '', 1775, '2018-07-13 10:53:41'),
(40, 36, 2, 'Offline', '', 1775, '2018-07-13 10:54:01');

-- --------------------------------------------------------

--
-- Table structure for table `productdetails`
--

CREATE TABLE `productdetails` (
  `productId` int(11) NOT NULL,
  `productName` varchar(500) NOT NULL,
  `productCategory` int(11) NOT NULL,
  `productPrice` varchar(500) NOT NULL,
  `deliveryCharges` int(11) NOT NULL DEFAULT '55',
  `img1` varchar(1000) NOT NULL,
  `img2` varchar(1000) NOT NULL,
  `img3` varchar(1000) NOT NULL,
  `img4` varchar(1000) NOT NULL,
  `productShortDescription` varchar(500) NOT NULL,
  `productLongDescription` varchar(1000) NOT NULL,
  `color` varchar(500) NOT NULL,
  `size` varchar(500) NOT NULL,
  `productPostDate` date NOT NULL,
  `productUpdateDate` date NOT NULL,
  `userId` int(11) NOT NULL,
  `isProductAvailable` tinyint(1) NOT NULL,
  `isProductDelete` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productdetails`
--

INSERT INTO `productdetails` (`productId`, `productName`, `productCategory`, `productPrice`, `deliveryCharges`, `img1`, `img2`, `img3`, `img4`, `productShortDescription`, `productLongDescription`, `color`, `size`, `productPostDate`, `productUpdateDate`, `userId`, `isProductAvailable`, `isProductDelete`) VALUES
(1, 'hand pursr', 1, '150', 55, '../product_images/20180621_115653.png', '../product_images/20180621_115723.png', '../product_images/20180621_115741.png', '../product_images/20180621_115847.png', 'hcyc', 'hhjj xydyuvk gzfsyduf hxgugk', 'Red', 'Small', '2018-06-21', '0000-00-00', 1, 1, 0),
(2, 'Bags', 2, '250', 55, '../product_images/20180621_115952.png', '../product_images/20180621_120004.png', '', '', 'xuuj', 'ftimn Mario bmlutd zsryipl xvj', 'mix', 'Medium', '2018-06-21', '0000-00-00', 1, 1, 0),
(3, 'washable single sari cover', 3, '100', 55, '../product_images/20180621_120132.png', '../product_images/20180621_120143.png', '', '', 'fh', 'xbk', '', 'Small', '2018-06-21', '0000-00-00', 1, 1, 0),
(4, 'hanger of jwellary', 4, '250', 55, '../product_images/20180621_122058.png', '../product_images/20180621_122112.png', '', '', 'affhkl', 'xghl chik', 'all', 'Large', '2018-06-21', '0000-00-00', 1, 1, 0),
(11, 'poi', 2, '20', 55, '../product_images/20180625_165618.png', '', '', '', '', '', '', '', '2018-06-25', '0000-00-00', 2, 1, 0),
(12, 'dfd', 1, '125', 55, '../product_images/myScale.png', '../product_images/Jellyfish.jpg', '../product_images/Penguins.jpg', '../product_images/Lighthouse.jpg', '', '', 'Red', 'Small', '2018-06-25', '0000-00-00', 1, 1, 0),
(13, 'dfd', 1, '125', 55, '../product_images/myScale.png', '../product_images/Jellyfish.jpg', '../product_images/Penguins.jpg', '../product_images/Lighthouse.jpg', 'frqr', ' rerber erber ej bterj tjer tre tj tnbreg et ', 'Red', 'Small', '2018-06-25', '0000-00-00', 1, 1, 0),
(14, 'dfd', 1, '125', 55, '../product_images/myScale.png', '../product_images/Jellyfish.jpg', '../product_images/Penguins.jpg', '../product_images/Lighthouse.jpg', 'frqr', ' rerber erber ej bterj tjer tre tj tnbreg et ', 'Red', 'Small', '2018-06-25', '0000-00-00', 1, 1, 0),
(15, 'rtd', 3, '350', 55, '../product_images/20180625_171532.png', '', '', '', '', '', '', '', '2018-06-25', '0000-00-00', 2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `productratingreview`
--

CREATE TABLE `productratingreview` (
  `ratingReviewId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` varchar(10) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` varchar(500) NOT NULL,
  `entryDate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userdetails`
--

CREATE TABLE `userdetails` (
  `userId` int(11) NOT NULL,
  `userFirstname` varchar(100) NOT NULL,
  `userLastName` varchar(100) NOT NULL,
  `userMobileNo` varchar(100) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `isEmailVerified` tinyint(1) NOT NULL,
  `isUserActive` tinyint(1) NOT NULL,
  `userEntryDate` date NOT NULL,
  `userUpdateDetailsDate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userdetails`
--

INSERT INTO `userdetails` (`userId`, `userFirstname`, `userLastName`, `userMobileNo`, `userEmail`, `password`, `otp`, `isEmailVerified`, `isUserActive`, `userEntryDate`, `userUpdateDetailsDate`) VALUES
(1, 'Pratik', 'Sonawane', '8655883062', 'sonawane.ptk@gmail.com', '123', '', 0, 1, '2018-06-21', '2018-06-21'),
(2, 'Pratik', 'Sonawane', '8655883062', 'sonawane.ptk@gmail.com', '123', '', 1, 1, '2018-06-21', '2018-06-21');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishListId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addressdetails`
--
ALTER TABLE `addressdetails`
  ADD PRIMARY KEY (`addressId`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `firebasetokensdetails`
--
ALTER TABLE `firebasetokensdetails`
  ADD PRIMARY KEY (`firebaseTokensId`);

--
-- Indexes for table `orderdeliverychages`
--
ALTER TABLE `orderdeliverychages`
  ADD PRIMARY KEY (`orderDeliveryChagesId`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`orderId`);

--
-- Indexes for table `orderstatus`
--
ALTER TABLE `orderstatus`
  ADD PRIMARY KEY (`orderStatusId`);

--
-- Indexes for table `paymentdetails`
--
ALTER TABLE `paymentdetails`
  ADD PRIMARY KEY (`paymentId`);

--
-- Indexes for table `productdetails`
--
ALTER TABLE `productdetails`
  ADD PRIMARY KEY (`productId`);

--
-- Indexes for table `productratingreview`
--
ALTER TABLE `productratingreview`
  ADD PRIMARY KEY (`ratingReviewId`);

--
-- Indexes for table `userdetails`
--
ALTER TABLE `userdetails`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishListId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addressdetails`
--
ALTER TABLE `addressdetails`
  MODIFY `addressId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `firebasetokensdetails`
--
ALTER TABLE `firebasetokensdetails`
  MODIFY `firebaseTokensId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderdeliverychages`
--
ALTER TABLE `orderdeliverychages`
  MODIFY `orderDeliveryChagesId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `orderstatus`
--
ALTER TABLE `orderstatus`
  MODIFY `orderStatusId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `paymentdetails`
--
ALTER TABLE `paymentdetails`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `productdetails`
--
ALTER TABLE `productdetails`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `productratingreview`
--
ALTER TABLE `productratingreview`
  MODIFY `ratingReviewId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userdetails`
--
ALTER TABLE `userdetails`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishListId` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
