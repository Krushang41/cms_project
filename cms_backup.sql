-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2024 at 09:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `image_compress`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `ImageID` int(10) UNSIGNED NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`ImageID`, `FileName`, `FilePath`, `CreatedAt`) VALUES
(1, '1731958554_SampleJPGImage_1mbmb.jpg', '../uploads/1731958554_SampleJPGImage_1mbmb.jpg', '2024-11-18 19:35:54'),
(2, '1731959325_SampleJPGImage_1mbmb.jpg', '../uploads/1731959325_SampleJPGImage_1mbmb.jpg', '2024-11-18 19:48:45'),
(3, '1731959325_SampleJPGImage_1mbmb.jpg', '../uploads/1731959325_SampleJPGImage_1mbmb.jpg', '2024-11-18 19:48:45'),
(4, '1731959325_SampleJPGImage_1mbmb.jpg', '../uploads/1731959325_SampleJPGImage_1mbmb.jpg', '2024-11-18 19:48:45'),
(5, '1731960661_WhatsApp Image 2024-11-18 at 14.08.48_bdcf3408.jpg', '../uploads/1731960661_WhatsApp Image 2024-11-18 at 14.08.48_bdcf3408.jpg', '2024-11-18 20:11:01'),
(6, '1731960677_WhatsApp Image 2024-11-18 at 14.08.48_fa2da949.jpg', '../uploads/1731960677_WhatsApp Image 2024-11-18 at 14.08.48_fa2da949.jpg', '2024-11-18 20:11:17'),
(7, '1731960689_WhatsApp Image 2024-11-18 at 14.08.48_d2262d51.jpg', '../uploads/1731960689_WhatsApp Image 2024-11-18 at 14.08.48_d2262d51.jpg', '2024-11-18 20:11:29'),
(8, '1731960700_WhatsApp Image 2024-11-18 at 14.08.48_3b3f8270.jpg', '../uploads/1731960700_WhatsApp Image 2024-11-18 at 14.08.48_3b3f8270.jpg', '2024-11-18 20:11:40'),
(9, '1731960731_WhatsApp Image 2024-11-18 at 14.08.50_01d01fa1.jpg', '../uploads/1731960731_WhatsApp Image 2024-11-18 at 14.08.50_01d01fa1.jpg', '2024-11-18 20:12:11'),
(10, '1731960742_WhatsApp Image 2024-11-18 at 14.08.50_1deae1f5.jpg', '../uploads/1731960742_WhatsApp Image 2024-11-18 at 14.08.50_1deae1f5.jpg', '2024-11-18 20:12:22'),
(11, '1731960749_WhatsApp Image 2024-11-18 at 14.08.51_98a54fa9.jpg', '../uploads/1731960749_WhatsApp Image 2024-11-18 at 14.08.51_98a54fa9.jpg', '2024-11-18 20:12:29'),
(12, '1731960759_WhatsApp Image 2024-11-18 at 14.08.50_ff708502.jpg', '../uploads/1731960759_WhatsApp Image 2024-11-18 at 14.08.50_ff708502.jpg', '2024-11-18 20:12:39'),
(13, '1731960769_WhatsApp Image 2024-11-18 at 14.08.51_3065a8a9.jpg', '../uploads/1731960769_WhatsApp Image 2024-11-18 at 14.08.51_3065a8a9.jpg', '2024-11-18 20:12:49'),
(14, '1731960777_WhatsApp Image 2024-11-18 at 14.08.52_a1b46332.jpg', '../uploads/1731960777_WhatsApp Image 2024-11-18 at 14.08.52_a1b46332.jpg', '2024-11-18 20:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `LogID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Action` varchar(255) DEFAULT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `PageID` int(10) UNSIGNED NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Content` text DEFAULT NULL,
  `ImageID` int(10) UNSIGNED DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`PageID`, `Title`, `Content`, `ImageID`, `CreatedAt`, `UpdatedAt`) VALUES
(12, 'Home', 'Welcome to our website! Explore our services and products.', 5, '2024-11-18 19:55:24', '2024-11-18 20:11:01'),
(13, 'About Us', 'Learn more about our mission, vision, and values.', 6, '2024-11-18 19:55:24', '2024-11-18 20:11:17'),
(14, 'Services', 'We offer a wide range of services to meet your needs.', 7, '2024-11-18 19:55:24', '2024-11-18 20:11:29'),
(15, 'Contact Us', 'Reach out to us through email, phone, or visit our office.', 8, '2024-11-18 19:55:24', '2024-11-18 20:11:40'),
(16, 'Blog', 'Check out our latest blog posts and updates.', 9, '2024-11-18 19:55:24', '2024-11-18 20:12:11'),
(17, 'Careers', 'Join our team and grow with us. Explore current job openings.', 10, '2024-11-18 19:55:24', '2024-11-18 20:12:22'),
(18, 'Privacy Policy', 'Read about how we handle your data and ensure your privacy.', 11, '2024-11-18 19:55:24', '2024-11-18 20:12:29'),
(19, 'Terms of Service', 'Understand the terms and conditions of using our services.', 12, '2024-11-18 19:55:24', '2024-11-18 20:12:39'),
(20, 'FAQ', 'Find answers to the most frequently asked questions.', 13, '2024-11-18 19:55:24', '2024-11-18 20:12:49'),
(21, 'Portfolio', 'Browse through our past projects and achievements.', 14, '2024-11-18 19:55:24', '2024-11-18 20:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `IsAdmin` tinyint(1) DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `PasswordHash`, `IsAdmin`, `CreatedAt`) VALUES
(1, 'admin', '$2y$10$W6jK6FkycN7GQNFUia.OLOF6C2nP7akgd.veQ1UVOTlP7LrnC2R7K', 1, '2024-11-18 19:34:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`ImageID`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`PageID`),
  ADD KEY `ImageID` (`ImageID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `ImageID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `PageID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`ImageID`) REFERENCES `images` (`ImageID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
