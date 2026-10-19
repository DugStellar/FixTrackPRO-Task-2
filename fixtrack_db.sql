-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2026 at 08:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fixtrack_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `broadcast_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `title`, `message`, `broadcast_date`, `created_at`) VALUES
(1, 'Welcome to FixTrack Pro', 'Event repair tracking portal is now live.', '2026-10-18 23:41:03', '2026-10-19 07:50:03'),
(2, 'Session 1 Scheduled', 'Hardware diagnostic overview starts at 10:00 AM.', '2026-10-18 23:41:03', '2026-10-19 07:50:03'),
(3, 'Keynote Address', 'Cape IT Assist Director welcome address at 11:00 AM.', '2026-10-18 23:41:03', '2026-10-19 07:50:03'),
(4, 'Lunch Break', 'Catering available in the main hall.', '2026-10-18 23:41:03', '2026-10-19 07:50:03'),
(5, 'Software Patching Workshop', 'Hands-on malware recovery session starting at 01:30 PM.', '2026-10-18 23:41:03', '2026-10-19 07:50:03'),
(6, 'Raffle Drawing Notice', 'Live raffle draw scheduled for 03:00 PM on stage.', '2026-10-18 23:41:03', '2026-10-19 07:50:03'),
(7, 'Network Security Q&A', 'Join our cybersecurity team at Booth B.', '2026-10-18 23:41:03', '2026-10-19 07:50:03'),
(8, 'Component Replacement Update', 'GPU repair station open for inquiries.', '2026-10-18 23:41:03', '2026-10-19 07:50:03'),
(9, 'Closing Ceremony', 'Wrap-up and prize distributions at 04:30 PM.', '2026-10-18 23:41:03', '2026-10-19 07:50:03'),
(10, 'Event Feedback Form', 'Please complete the feedback survey in the mobile app.', '2026-10-18 23:41:03', '2026-10-19 07:50:03');

-- --------------------------------------------------------

--
-- Table structure for table `prizes`
--

CREATE TABLE `prizes` (
  `prize_id` int(11) NOT NULL,
  `prize_name` varchar(255) NOT NULL,
  `prize_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prizes`
--

INSERT INTO `prizes` (`prize_id`, `prize_name`, `prize_description`) VALUES
(1, '1st Prize', 'R10,000 Cash'),
(2, '2nd Prize', 'Laptop'),
(3, '3rd Prize', 'Tablet'),
(4, '4th Prize', 'Smartwatch');

-- --------------------------------------------------------

--
-- Table structure for table `raffles`
--

CREATE TABLE `raffles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raffles`
--

INSERT INTO `raffles` (`id`, `title`, `description`, `status`, `created_at`) VALUES
(1, 'Community Hero Raffle #1', 'Participate in active municipal feedback draws and win community perks!', 'ACTIVE', '2026-10-19 17:21:13');

-- --------------------------------------------------------

--
-- Table structure for table `raffle_entries`
--

CREATE TABLE `raffle_entries` (
  `raffle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_number` varchar(50) NOT NULL,
  `is_winner` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raffle_entries`
--

INSERT INTO `raffle_entries` (`raffle_id`, `user_id`, `ticket_number`, `is_winner`) VALUES
(1, 1, 'TICK-1001', 0),
(2, 2, 'TICK-1002', 0),
(3, 3, 'TICK-1003', 0),
(4, 4, 'TICK-1004', 0),
(5, 5, 'TICK-1005', 0),
(6, 6, 'TICK-1006', 0),
(7, 7, 'TICK-1007', 0),
(8, 8, 'TICK-1008', 0),
(9, 9, 'TICK-1009', 0),
(10, 10, 'TICK-1010', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Pending','In Progress','Resolved') DEFAULT 'Pending',
  `upvotes` int(11) DEFAULT 0,
  `raffle_ticket` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `title`, `category`, `description`, `status`, `upvotes`, `raffle_ticket`, `created_at`) VALUES
(1, 1, 'Wi-Fi Down in Block B', 'Network', 'Cannot connect to student portal network.', 'Pending', 0, 'FIX-10421', '2026-10-19 12:59:45'),
(2, 1, 'Projector Bulb Faded', 'Hardware', 'Lecture room 4 projector is too dim to read.', 'In Progress', 0, 'FIX-88412', '2026-10-19 12:59:45'),
(3, 1, 'Door Lock Broken', 'Facilities', 'Lab 2 door handle is stuck shut.', 'Resolved', 0, 'FIX-33921', '2026-10-19 12:59:45');

-- --------------------------------------------------------

--
-- Table structure for table `rsvps`
--

CREATE TABLE `rsvps` (
  `rsvp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attending_status` enum('Yes','No') NOT NULL,
  `dietary_notes` varchar(255) DEFAULT 'None'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rsvps`
--

INSERT INTO `rsvps` (`rsvp_id`, `user_id`, `attending_status`, `dietary_notes`) VALUES
(1, 1, 'Yes', 'None'),
(2, 2, 'Yes', 'Vegetarian'),
(3, 3, 'Yes', 'None'),
(4, 4, 'Yes', 'Halal'),
(5, 5, 'No', 'N/A'),
(6, 6, 'Yes', 'Gluten Free'),
(7, 7, 'Yes', 'None'),
(8, 8, 'No', 'N/A'),
(9, 9, 'Yes', 'None'),
(10, 10, 'Yes', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Admin','Guest') DEFAULT 'Guest',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `role`, `created_at`, `name`) VALUES
(1, 'Devon Adams', 'devon@capeit.co.za', '$2y$10$wT8K8U1pL4u7E2A9qO3Z1e9eX0J5vK8U1pL4u7E2A9qO3Z1e9eX0J', 'Admin', '2026-10-18 23:41:03', NULL),
(2, 'Keeyan Bowman', 'keeyan@capeit.co.za', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe11yR4N32f3A881g9N13d2S15tP6u57a', 'Admin', '2026-10-18 23:41:03', NULL),
(3, 'Tristan Booysen', 'tristan@capeit.co.za', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe11yR4N32f3A881g9N13d2S15tP6u57a', 'Admin', '2026-10-18 23:41:03', NULL),
(4, 'John Doe', 'john.doe@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe11yR4N32f3A881g9N13d2S15tP6u57a', 'Guest', '2026-10-18 23:41:03', NULL),
(5, 'Jane Smith', 'jane.smith@yahoo.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe11yR4N32f3A881g9N13d2S15tP6u57a', 'Guest', '2026-10-18 23:41:03', NULL),
(6, 'Michael Brown', 'mbrown@outlook.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe11yR4N32f3A881g9N13d2S15tP6u57a', 'Guest', '2026-10-18 23:41:03', NULL),
(7, 'Sarah Connor', 'sconnor@cyberdyne.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe11yR4N32f3A881g9N13d2S15tP6u57a', 'Guest', '2026-10-18 23:41:03', NULL),
(8, 'Bruce Wayne', 'bruce@wayneent.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe11yR4N32f3A881g9N13d2S15tP6u57a', 'Guest', '2026-10-18 23:41:03', NULL),
(9, 'Clark Kent', 'ckent@dailyplanet.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe11yR4N32f3A881g9N13d2S15tP6u57a', 'Guest', '2026-10-18 23:41:03', NULL),
(10, 'Peter Parker', 'pparker@dailybugle.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe11yR4N32f3A881g9N13d2S15tP6u57a', 'Guest', '2026-10-18 23:41:03', NULL),
(12, 'John Doe', 'john@example.com', 'password123', '', '2026-10-19 03:24:42', NULL),
(13, 'stellar', 'stellar@gmail.com', '$2y$10$sIWa/6MN9XZmx3FJBoqtIe318IqnvlS2iUqVMFDMXskgtfXe.7k9W', '', '2026-10-19 03:36:41', NULL),
(14, 'deveeee', 'devs@gmail.com', '$2y$10$.VYVvXrF2MoSiEaN6/OW1.u.iWB4kQRAs5m5TTLFC6.XL/wfIdZK.', '', '2026-10-19 08:10:11', NULL),
(15, 'tris', 'tris@gmail.com', '$2y$10$qNvlkaPi1O/Rgb4QO95ex.bYkNI5LEqpqyB0omEQQgNhg7hPCkoNe', '', '2026-10-19 11:39:17', NULL),
(16, 'keydo', 'keydo@gmail.com', '$2y$10$p0z.UCJZzKgUOT09dg3WkOZzpSppRF2KQK/aAfA8AdOmSO6N3XnFi', '', '2026-10-19 12:05:50', NULL),
(17, 'james', 'james@gmail.com', '$2y$10$xpTON3A4jeIz0bZI/ZgoPeateUlEIfmUFpiV5f.t2hthN7qE654dO', '', '2026-10-19 13:53:42', NULL),
(18, 'System Admin', 'admin@fixtrack.com', 'admin123', 'Admin', '2026-10-19 14:05:36', NULL),
(19, 'Test Guest', 'guest@fixtrack.com', 'guest123', 'Guest', '2026-10-19 16:47:17', NULL),
(20, 'jan', 'jan@gmail.com', '$2y$10$bHClYpfj/oxHLQGVG4dhZe6XXNUT5Ec4yxc/aNvD3B35GBnx6S8LS', '', '2026-10-19 17:23:59', NULL),
(21, 'june', 'june@gmail.com', '$2y$10$FEj4pbfUePw82xsgdWE4LeWNVHb.EVecjdMAX5dtwI2HFAVD4PPD2', '', '2026-10-19 17:42:53', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `prizes`
--
ALTER TABLE `prizes`
  ADD PRIMARY KEY (`prize_id`);

--
-- Indexes for table `raffles`
--
ALTER TABLE `raffles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raffle_entries`
--
ALTER TABLE `raffle_entries`
  ADD PRIMARY KEY (`raffle_id`),
  ADD UNIQUE KEY `ticket_number` (`ticket_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rsvps`
--
ALTER TABLE `rsvps`
  ADD PRIMARY KEY (`rsvp_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `prizes`
--
ALTER TABLE `prizes`
  MODIFY `prize_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `raffles`
--
ALTER TABLE `raffles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `raffle_entries`
--
ALTER TABLE `raffle_entries`
  MODIFY `raffle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rsvps`
--
ALTER TABLE `rsvps`
  MODIFY `rsvp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `raffle_entries`
--
ALTER TABLE `raffle_entries`
  ADD CONSTRAINT `raffle_entries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `rsvps`
--
ALTER TABLE `rsvps`
  ADD CONSTRAINT `rsvps_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
