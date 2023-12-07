-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2023 at 04:51 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventmanager`
--

-- --------------------------------------------------------

--
-- Table structure for table `asistencias`
--

CREATE TABLE `asistencias` (
  `id` bigint(20) NOT NULL,
  `invitacion` bigint(20) NOT NULL,
  `acompanantes` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `invitaciones`
--

CREATE TABLE `invitaciones` (
  `numero` bigint(20) NOT NULL,
  `invitado` varchar(250) NOT NULL,
  `acompanantes` smallint(6) NOT NULL,
  `mesa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invitaciones`
--

INSERT INTO `invitaciones` (`numero`, `invitado`, `acompanantes`, `mesa`) VALUES
(1, 'Invitado 1', 3, 1),
(2, 'Invitado 2', 2, 2),
(3, 'Invitado 3', 1, 3),
(4, 'Invitado 4', 1, 3),
(5, 'Invitado 5', 5, 4),
(6, 'Invitado 6', 2, 5),
(7, 'Invitado 7', 4, 6),
(8, 'Invitado 8', 8, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invitacion` (`invitacion`);

--
-- Indexes for table `invitaciones`
--
ALTER TABLE `invitaciones`
  ADD PRIMARY KEY (`numero`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invitaciones`
--
ALTER TABLE `invitaciones`
  MODIFY `numero` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`invitacion`) REFERENCES `invitaciones` (`numero`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
