-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 17 أبريل 2025 الساعة 22:28
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_system`
--

-- --------------------------------------------------------

--
-- بنية الجدول `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `published_year` int(11) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `pdf_link` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `genre`, `published_year`, `cover_image`, `pdf_link`, `user_id`) VALUES
(7, 'Harry Potter and the Sorcerer\'s Stone', ' J.K. Rowling', 'Fantasy', 1997, 'HarryPotter.jpg', 'https://pwc.res.zabanshenas.ir/Harry_Potter_and_the_Sorcerer_s_Stone_www_libpdf_blog_ir_aba92c0a66.pdf', 0),
(14, 'Alice\'s Adventures in Wonderland	', 'Lewis Carroll', 'Fantasy / Adventure', 1965, 'alices.jpg', 'https://www.planetebook.com/free-ebooks/alices-adventures-in-wonderland.pdf', 0),
(15, 'The Alchemist', 'Paulo Coelho', 'Fiction', 1988, 'PauloCoelho.jpg', 'https://brooksplanning.wordpress.com/wp-content/uploads/2016/06/the-alchemist-paulo-coelho.pdf', 0),
(16, 'The Little Prince', 'Antoine de Saint-Exupéry', 'Philosophical/Fantasy', 1943, 'thelittleprince.jpg', 'https://blogs.ubc.ca/edcp508/files/2016/02/TheLittlePrince.pdf', 0),
(17, 'And then there were none', 'Agatha Christie', 'Mystery/Crime', 1939, 'andthenthere.jpg', 'https://www.tongaatsecondary.co.za/gallery/Swiss%20Family.pdf', 0),
(18, 'And then there were none', 'Agatha Christie', 'Mystery/Crime', 1939, 'andthenthere.jpg', 'https://www.tongaatsecondary.co.za/gallery/Swiss%20Family.pdf', 4);

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(2, 'aa', 'h1234567'),
(3, 'Hateef5', 'h1234567'),
(4, 'test1', 'h1234567');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
