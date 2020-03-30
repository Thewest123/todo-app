-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 30. bře 2020, 21:13
-- Verze serveru: 10.4.11-MariaDB
-- Verze PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `todos_db`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `todos`
--

CREATE TABLE `todos` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(25) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `is_completed` tinyint(1) NOT NULL,
  `deadline` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `todos`
--

INSERT INTO `todos` (`id`, `name`, `desc`, `is_completed`, `deadline`) VALUES
(19, 'VYP', 'Prezentace Weedeček.cz', 0, '2020-03-30'),
(20, 'FYZ', 'Pokus', 1, '2020-03-30'),
(21, 'ČJL', 'Pracovní list Kafka', 0, '2020-04-03'),
(22, 'WBA', 'Todo Aplikace', 1, '2020-06-01'),
(23, 'ITP', 'Databáze', 0, '2020-03-23'),
(24, 'Radko', 'Nahrát podcast.. proč?!..', 0, '2020-04-14'),
(25, 'Unity', 'Herní vývoj', 0, '2020-04-26'),
(26, 'VYP', 'Brožurka, letáček Weedeček.cz', 1, '2020-03-23'),
(27, 'Hello World', 'Lorem ipsum dolor sit amet', 0, NULL),
(50, 'ONA', 'Referát Islám', 0, '2020-04-12'),
(51, 'OSE', 'Chemický pokus', 1, '2020-03-31'),
(52, 'ITP', 'Analýza průběhu koronaviru', 0, '2020-04-30'),
(53, 'EKO', 'Výpisky pro Chladinu', 0, NULL),
(54, 'Todo', 'Které nemá deadline', 1, NULL),
(55, 'Longest title ever huuhh?', 'The longest description ever. Lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lore', 0, NULL);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `todos`
--
ALTER TABLE `todos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
