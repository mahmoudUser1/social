-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2026 at 02:21 PM
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
-- Database: `social`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `from-id` int(11) NOT NULL,
  `to-id` int(11) NOT NULL,
  `messages` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created-at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `from-id`, `to-id`, `messages`, `created-at`) VALUES
(1, 1, 2, 'mahmoud maher', '2026-06-01'),
(2, 1, 2, 'tydotdrkuoptoptuit', '2026-06-01'),
(3, 1, 2, 'عامل ايه يا رجل يا جدع', '2026-06-01'),
(4, 1, 2, '..............', '2026-06-01'),
(5, 1, 2, '..............\r\n..........', '2026-06-01'),
(6, 1, 2, '.........................................', '2026-06-01'),
(7, 1, 4, 'vxfddg', '2026-06-01'),
(8, 1, 4, 'fdszgdggfd\r\nsfXDxz\r\nfdxczfzxc', '2026-06-01'),
(9, 1, 2, 'انا اسمي محمود', '2026-06-01'),
(10, 1, 2, 'انا \r\nاسمي\r\nمحمود', '2026-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user-id` int(11) NOT NULL,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created-at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user-id`, `content`, `created-at`) VALUES
(9, 1, 'hello', '2026-06-01'),
(10, 1, ' welcome', '2026-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created-at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created-at`) VALUES
(1, 'Mahmoud Maher', 'tea0mah2009@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2026-06-01'),
(2, 'ahmad mahmood', 'ahmad@gmail.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', '2026-06-01'),
(3, 'reme maher', 'reme@gmail.com', '7c222fb2927d828af22f592134e8932480637c0d', '2026-06-01'),
(4, 'Ahmed Ali', 'ahmed1@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-11-15'),
(5, 'Mohamed Hassan', 'mohamed2@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-12-01'),
(6, 'Omar Samy', 'omar3@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2026-01-07'),
(7, 'Youssef Adel', 'youssef4@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2026-02-11'),
(8, 'Mahmoud Tarek', 'mahmoud5@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2026-03-09'),
(9, 'Ali Mostafa', 'ali6@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-10-21'),
(10, 'Khaled Emad', 'khaled7@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-09-30'),
(11, 'Amr Hany', 'amr8@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2026-04-15'),
(12, 'Karim Nabil', 'karim9@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-08-18'),
(13, 'Ibrahim Saeed', 'ibrahim10@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2026-05-01'),
(14, 'Sara Ahmed', 'sara11@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-07-12'),
(15, 'Mona Adel', 'mona12@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-06-05'),
(16, 'Nour Hassan', 'nour13@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2026-06-20'),
(17, 'Aya Samir', 'aya14@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-05-14'),
(18, 'Fatma Ali', 'fatma15@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2026-07-03'),
(19, 'Mariam Emad', 'mariam16@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-04-08'),
(20, 'Huda Mostafa', 'huda17@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2026-08-11'),
(21, 'Salma Tarek', 'salma18@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-03-19'),
(22, 'Reem Khaled', 'reem19@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2026-09-01'),
(23, 'Habiba Nasser', 'habiba20@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2025-02-25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `f-id` (`from-id`),
  ADD KEY `t-id` (`to-id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p-id` (`user-id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `f-id` FOREIGN KEY (`from-id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `t-id` FOREIGN KEY (`to-id`) REFERENCES `users` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `p-id` FOREIGN KEY (`user-id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
