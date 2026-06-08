-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2026 at 08:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cacti_kuching_db`
--
CREATE DATABASE IF NOT EXISTS `cacti_kuching_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cacti_kuching_db`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'Admin', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

DROP TABLE IF EXISTS `enquiry`;
CREATE TABLE `enquiry` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `subject` varchar(50) NOT NULL,
  `comments` text NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `enquiry_status` varchar(30) NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enquiry`
--

INSERT INTO `enquiry` (`id`, `fname`, `lname`, `email`, `phone`, `subject`, `comments`, `submission_date`, `submitted_at`, `enquiry_status`) VALUES
(1, 'Rachael', 'Bong', 'rachaelbong@gmail.com', '0123334444', 'Standard Package', 'hi', '2026-06-07 14:55:24', '2026-06-07 14:55:24', 'In Progress'),
(2, 'Ali', 'Ali', 'ali@gmail.com', '0123334466', 'The Plant Hospital', 'hey', '2026-06-07 14:55:51', '2026-06-07 14:55:51', 'Resolved');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `product1` varchar(100) NOT NULL,
  `quantity1` int(11) NOT NULL,
  `product2` varchar(100) DEFAULT NULL,
  `quantity2` int(11) DEFAULT NULL,
  `product3` varchar(100) DEFAULT NULL,
  `quantity3` int(11) DEFAULT NULL,
  `delivery` varchar(50) NOT NULL,
  `payment` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(30) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `product1`, `quantity1`, `product2`, `quantity2`, `product3`, `quantity3`, `delivery`, `payment`, `date`, `time`, `name`, `email`, `phone`, `address`, `order_date`, `order_status`) VALUES
(1, 'Echeveria Lola', 1, 'Old Lady', 2, 'Bunny Ear', 2, 'delivery', 'online', '2026-06-12', 'morning', 'Rachael Bong', 'rachaelbong@gmail.com', '0123334444', 'cactus street1, Kuching, Sarawak, 93350', '2026-06-07 14:56:13', 'Delivered'),
(2, 'Cardon', 1, '', 0, '', 0, 'delivery', 'cash', '2026-06-13', 'morning', 'Amy', 'amy@gmail.com', '0123334499', 'cactus street9, Kuching, Sarawak, 93350', '2026-06-07 14:57:03', 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `product_options` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_source` varchar(500) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product_name`, `category`, `product_options`, `description`, `price`, `image_path`, `image_source`, `stock_quantity`) VALUES
(1, 'Golden Barrel', 'Cacti', '', 'E. grusonii.', 35.00, 'images/cacti-golden-barrel.jpg', 'https://www.pexels.com/photo/green-cactus-plants-11678322/', 1),
(2, 'Bunny Ear', 'Cacti', '', 'O. microdasys.', 20.00, 'images/uploads/bunny-ear-cactus_1780636063.jpg', 'https://share.google/AdExFbk3JFufq1S44', 8),
(3, 'Old Lady', 'Cacti', NULL, 'M. hahniana.', 32.00, 'images/cacti-old-lady.jpg', 'https://www.pexels.com/photo/blooming-cactus-in-pot-16667048/', 46),
(4, 'Princess of Night', 'Cacti', '', 'C. jamacaru.', 55.00, 'images/cacti-princess-of-the-night.jpg', 'https://www.pexels.com/photo/blooming-cactus-at-sunset-in-brazilian-countryside-36769813/', 0),
(5, 'Prickly Pear', 'Cacti', NULL, 'O. ficus-indica.', 35.00, 'images/cacti-prickly-pear.jpg', 'https://unsplash.com/photos/cactus-plants-Npfkyf94cik', 50),
(6, 'Cardon', 'Cacti', NULL, 'P. pringlei.', 85.00, 'images/cacti-cardon-cactus.jpg', 'https://unsplash.com/photos/green-cactus-under-blue-sky-at-daytime-a_YGMhAa0e0', 49),
(7, 'Echeveria Lola', 'Succulents', '', 'E. laui.', 20.00, 'images/suc-amazing-grace.jpg', 'https://pin.it/7I8cqINrJ', 49),
(8, 'Jade Plant', 'Succulents', NULL, 'C. ovata.', 28.00, 'images/suc-jade-plant.jpg', 'https://pin.it/59rRfd60E', 50),
(9, 'Moonstones', 'Succulents', NULL, 'G. amethystinum.', 38.00, 'images/suc-moonstones.jpg', 'https://pin.it/6gsBKyem5', 50),
(10, 'Aloe Vera', 'Succulents', '', 'A. barbadensis.', 20.00, 'images/suc-aloe-vera.jpg', 'https://pin.it/8fKzJ9X2V', 0),
(11, 'Ghosty', 'Succulents', NULL, 'G. paraguayense.', 42.00, 'images/suc-ghosty.jpg', 'https://pin.it/6LwPxgoLj', 50),
(12, 'String of Pearls', 'Succulents', NULL, 'S. rowleyanus.', 50.00, 'images/suc-string-of-pearls.jpg', 'https://pin.it/33ZzdQ2ET', 49),
(13, 'Artisan Terracotta', 'Planting Accessories', 'Small 4\":15.00, Medium 6\":25.00, Large 8\":38.00', 'Breathable Clay.', 15.00, 'images/acc-terracotta-pot.jpg', 'https://pin.it/2WzknA4F8', NULL),
(14, 'Premium Cactus Mix', 'Planting Accessories', '1 Litre Bag:18, 3 Litre Bag:45', 'Fast-Draining.', 18.00, 'images/acc-cactus-soil.jpg', 'https://pin.it/6YrfMEAHy', NULL),
(15, 'Natural Pumice', 'Planting Accessories', 'Fine Grain:15, Medium Grain:18', 'Volcanic Rock.', 15.00, 'images/acc-pumice-stone.jpg', 'https://pin.it/1Rx7HkHvh', NULL),
(16, 'Long-Spout Can', 'Planting Accessories', '500 ml:50.00, 1 Litre:65', 'Stainless Steel.', 50.00, 'images/acc-watering-can.jpg', 'https://pin.it/2ntpM6Ih4', NULL),
(17, 'Planting Tweezers', 'Planting Accessories', 'Straight Tip:12, Curved Tip:15', 'Extra Long.', 12.00, 'images/acc-tweezers.jpg', 'https://pin.it/5SQixT7Jj', NULL),
(18, 'Pruning Shears', 'Planting Accessories', 'Standard Steel:35, Titanium Coated:50', 'Carbon Steel.', 35.00, 'images/acc-pruning-sheers.jpg', 'https://pin.it/5QugjbdMH', NULL),
(19, 'Liquid Cactus Food', 'Planting Accessories', '250 ml:22, 500 ml:32', 'Low-Nitrogen.', 22.00, 'images/acc-liquid-fertilizer.jpg', 'https://pin.it/1lPFa10eo', NULL),
(20, 'River Stone Dressing', 'Planting Accessories', 'Matte White:10.00, Natural Mixed:20.00', 'Decorative Stones.', 10.00, 'images/acc-top-dressing.jpg', 'https://pin.it/1O2jrIwtG', NULL),
(21, 'Soil Moisture Meter', 'Planting Accessories', 'Analog:30.00, Digital:75.00', 'Root-Level Tech.', 30.00, 'images/acc-moisture-meter.jpg', 'https://pin.it/4LvlLriA1', NULL),
(22, 'The Plant Hospital', 'Services', '', 'Did you overwater your succulent? Is your cactus stretching for light? Bring your struggling plants to our Kuching home-base. We provide professional root-rot treatments, precision pruning, and repotting in our premium gritty soil mix to bring your desert gems back to life.', 20.00, 'images/uploads/repotting_1780683542.jpg', '', 50),
(23, 'Custom Terrariums', 'Services', '', 'Perfect for office desks or unique gifts. We design and build enclosed or open-air glass terrariums tailored to your aesthetic. Choose your preferred glass shape, sand colors, and plant varieties, and we handle the complex layering required for proper drainage.', 65.00, 'images/uploads/terrarium_1780683561.jpg', '', 50),
(24, 'Terrarium Workshop', 'Services', '', 'Join us for a hands-on afternoon and learn the art of building your own desert landscape. We provide all the materials, including glass vessels, specialized gritty soil, decorative sands, and your choice of premium succulents. Perfect for weekend dates, team building, or just a relaxing solo activity!', 85.00, 'images/uploads/workshop_1780683583.jpg', '', 0),
(25, 'Plant Boarding', 'Services', '', 'Going on a long holiday? Don\'t leave your precious succulents to wither or get overwatered by well-meaning neighbors. Drop them off at our dedicated greenhouse. We provide optimal sunlight exposure, controlled watering schedules, and daily monitoring to ensure your plants thrive while you are away.', 5.00, 'images/uploads/plant_boarding_1780684207.jpg', '', 50),
(26, 'Standard Package', 'Services', '', 'Minimum 50 units. Includes standard Haworthia or Aloe, nursery plastic pot, and basic care instruction card.', 8.00, '', '', 50),
(27, 'Luxury Package', 'Services', '', 'Minimum 20 units. Includes imported Gymnocalycium, glazed ceramic pot, custom laser-cut wooden tags, and decorative top-dressing stones.', 25.00, '', '', 50),
(28, 'Premium Package', 'Services', '', 'Minimum 30 units. Includes rare Echeveria rosettes, artisan terracotta pot, and custom printed name tags.', 15.00, '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_option`
--

DROP TABLE IF EXISTS `product_option`;
CREATE TABLE `product_option` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `option_name` varchar(100) NOT NULL,
  `option_price` decimal(10,2) NOT NULL,
  `option_stock` int(11) NOT NULL DEFAULT 50
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_option`
--

INSERT INTO `product_option` (`id`, `product_id`, `option_name`, `option_price`, `option_stock`) VALUES
(4, 14, '1 Litre Bag', 18.00, 50),
(5, 14, '3 Litre Bag', 45.00, 50),
(6, 15, 'Fine Grain', 15.00, 50),
(7, 15, 'Medium Grain', 18.00, 50),
(8, 16, '500 ml', 50.00, 50),
(9, 16, '1 Litre', 65.00, 50),
(10, 17, 'Straight Tip', 12.00, 50),
(11, 17, 'Curved Tip', 15.00, 50),
(12, 18, 'Standard Steel', 35.00, 50),
(13, 18, 'Titanium Coated', 50.00, 50),
(14, 19, '250 ml', 22.00, 50),
(15, 19, '500 ml', 32.00, 50),
(18, 21, 'Analog', 30.00, 50),
(19, 21, 'Digital', 75.00, 50),
(26, 20, 'Matte White', 10.00, 1),
(27, 20, 'Natural Mixed', 20.00, 50),
(28, 13, 'Small 4\"', 15.00, 0),
(29, 13, 'Medium 6\"', 25.00, 49),
(30, 13, 'Large 8\"', 38.00, 50);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_pref` varchar(20) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fname`, `lname`, `email`, `phone`, `username`, `password`, `contact_pref`, `street`, `city`, `state`, `postcode`, `registration_date`) VALUES
(5, 'Rachael', 'Bong', 'rachaelbong@gmail.com', '0123334444', 'rachael', '1234', 'email', 'cactus street1', 'Kuching', 'Sarawak', '93350', '2026-05-16 11:16:18'),
(7, 'Amber', 'Choo', 'amberchoo@gmail.com', '0123456789', 'amberchoo', '1234', 'phone', 'cactus street2', 'pp', 'Pulau Pinang', '93350', '2026-05-24 06:53:40'),
(8, 'Eleona', 'Kee', 'eleonakee@gmail.com', '0123456788', 'eleonakee', '1234', 'phone', 'cactus street3', 'Kuching', 'Sarawak', '93350', '2026-05-24 16:58:58'),
(9, 'a', 'bc', 'abc@gmail.com', '01133344444', 'abc', '1234', 'email', 'cactus street4', 'Pahang', 'Pahang', '93350', '2026-06-06 10:42:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_option`
--
ALTER TABLE `product_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `product_option`
--
ALTER TABLE `product_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_option`
--
ALTER TABLE `product_option`
  ADD CONSTRAINT `product_option_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
