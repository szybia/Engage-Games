-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2017 at 12:23 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `engage`
--

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `game_id` int(3) NOT NULL,
  `title` varchar(50) NOT NULL,
  `age` int(3) NOT NULL DEFAULT '3',
  `genre` varchar(30) NOT NULL,
  `console` varchar(10) NOT NULL,
  `price` double(4,2) NOT NULL,
  `developer` varchar(40) NOT NULL,
  `release_date` date NOT NULL,
  `cover_path` varchar(40) NOT NULL,
  `deal` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store information about games.';

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`game_id`, `title`, `age`, `genre`, `console`, `price`, `developer`, `release_date`, `cover_path`, `deal`) VALUES
(1, 'Alien Isolation', 18, 'Survival Horror', 'XB1', 19.99, 'Creative Assembly', '2014-10-07', 'alien-isolation.png', 1),
(2, 'Battlefield 1', 18, 'FPS', 'XB1', 39.99, 'EA DICE', '2016-10-21', 'battlefield-1.jpg', 0),
(3, 'Battlefield 1', 18, 'FPS', 'PS4', 39.99, 'EA DICE', '2016-10-21', 'battlefield-1.jpg', 1),
(4, 'Star Wars Battlefront 2', 15, 'FPS', 'XB1', 74.99, 'EA DICE', '2017-11-17', 'battlefront.png', 0),
(5, 'Call of Duty World War 2', 18, 'FPS', 'XB1', 74.99, 'Sledgehammer Games', '2017-11-03', 'codww2.jpg', 0),
(6, 'Counter Strike Global Offensive', 15, 'FPS', 'PC', 4.79, 'Valve', '2012-08-21', 'cs-go.jpg', 0),
(7, 'Dark Souls 3', 18, 'Action role-playing', 'PS4', 29.99, 'FromSoftware', '2016-04-12', 'dark-souls-3.jpeg', 0),
(8, 'Destiny 2', 16, 'Action role-playing', 'XB1', 74.99, 'Bungie', '2017-10-24', 'destiny2.jpg', 1),
(11, 'Doom', 18, 'FPS', 'PS4', 24.99, 'id Software', '2016-05-13', 'doom.jpg', 0),
(12, 'FIFA 15', 3, 'Sports', 'XB1', 4.99, 'EA SPORTS', '2014-09-23', 'fifa-15.jpg', 0),
(13, 'FIFA 16', 3, 'Sports', 'PC', 24.99, 'EA SPORTS', '2015-09-24', 'fifa-16.jpg', 0),
(14, 'FIFA 16', 3, 'Sports', 'PS4', 24.99, 'EA SPORTS', '2015-09-24', 'fifa-16.jpg', 0),
(15, 'FIFA 17', 3, 'Sports', 'XB1', 39.99, 'EA SPORTS', '2016-09-24', 'fifa-17.jpg', 0),
(16, 'FIFA 18', 3, 'Sports', 'PS4', 74.99, 'EA SPORTS', '2017-09-24', 'fifa18.png', 1),
(17, 'For Honor', 18, 'Action', 'PC', 24.99, 'Ubisoft Montreal', '2017-02-14', 'for-honor.jpg', 0),
(18, 'Grand Theft Auto 5', 18, 'Action-Adventure', 'PC', 19.99, 'Rockstar North', '2015-04-14', 'gta-5.png', 0),
(19, 'Grand Theft Auto 5', 18, 'Action-Adventure', 'XB1', 19.99, 'Rockstar North', '2015-04-14', 'gta-5.png', 0),
(20, 'Grand Theft Auto San Andreas', 18, 'Action-adventure', 'PC', 4.99, 'Rockstar North', '2004-10-26', 'gta-san-andreas.jpg', 1),
(21, 'Half Life 2', 16, 'FPS', 'PC', 3.99, 'Valve', '2004-11-16', 'half-life-2.jpg', 0),
(22, 'Hitman Absolution', 18, 'Stealth', 'PC', 24.99, 'IO Interactive', '2012-11-20', 'hitman-absolution.jpg', 0),
(23, 'Horizon Zero Dawn', 16, 'Action role-playing', 'PS4', 49.99, 'Guerilla Games', '2017-02-28', 'horizon-zero-dawn.jpg', 1),
(24, 'Injustice 2', 16, 'Fighting', 'XB1', 44.99, 'NetherRealm Studios', '2017-05-16', 'injustice-2-xbox.png', 0),
(25, 'Last of Us', 18, 'Action-adventure', 'PS4', 29.99, 'Naughty Dog', '2013-06-14', 'last-of-us.jpg', 1),
(26, 'Mortal Combat X', 18, 'Fighting', 'PS4', 24.99, 'NetherRealm Studios', '2015-04-07', 'mortal-combat.jpg', 0),
(27, 'Assassin\'s Creed Origins', 16, 'Action-adventure', 'PC', 74.99, 'Ubisoft Montreal', '2017-10-27', 'origins.png', 0),
(28, 'Red Dead Redemption', 18, 'Action-adventure', 'XB1', 4.99, 'Rockstar San Diego', '2010-05-18', 'red-dead-redemption.jpg', 0),
(29, 'Resident Evil 7 Biohazard', 18, 'Survival Horror', 'PS4', 24.99, 'Capcom', '2017-01-24', 'resident-evil-7.jpg', 0),
(30, 'Rocket League', 3, 'Sports', 'PS4', 19.99, 'Psyonix', '2015-07-07', 'rocket-league.jpg', 0),
(31, 'Resident Evil 7 Biohazard', 18, 'Survival Horror', 'PC', 29.99, 'Capcom', '2017-01-24', 'resident-evil-7.jpg', 0),
(32, 'Rocket League', 3, 'Sports', 'PS4', 19.99, 'Psyonix', '2015-07-07', 'rocket-league.jpg', 0),
(33, 'Sniper Elite 4', 18, 'Shooter', 'PC', 39.99, 'Rebellion Developments', '2017-02-14', 'sniper.png', 0),
(34, 'Sniper Elite 4', 18, 'Shooter', 'PS4', 39.99, 'Rebellion Developments', '2017-02-14', 'sniper.png', 0),
(35, 'Titanfall 2', 12, 'FPS', 'XB1', 39.99, 'Respawn Entertainment', '2016-10-28', 'titanfall-2.jpg', 0),
(36, 'Uncharted 4', 16, 'Action-adventure', 'PS4', 34.99, 'Naughty Dog', '2016-05-10', 'uncharted-4.jpg', 0),
(37, 'Until Dawn', 16, 'Survival Horror', 'PS4', 4.99, 'Supermassive Games', '2015-08-25', 'until-dawn.jpg', 0),
(38, 'Watch Dogs 2', 16, 'Action-adventure', 'PS4', 29.99, 'Ubisoft Montreal', '2016-11-15', 'watch-dogs-2.jpg', 0),
(39, 'Witcher 3 Wild Hunt', 18, 'Action role-playing', 'PS4', 19.99, 'CD Projekt RED', '2015-05-19', 'witcher-3.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `game_id` int(3) NOT NULL,
  `email` varchar(60) NOT NULL,
  `quantity` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Weak entity to link users to games in their wishlist.' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `email` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(70) NOT NULL,
  `forgot_password_hash` varchar(70) DEFAULT NULL,
  `forgot_password_date` datetime DEFAULT NULL,
  `user_image_path` varchar(40) NOT NULL,
  `active` tinyint(1) DEFAULT '0',
  `remember_me_selector` varchar(32) DEFAULT NULL,
  `remember_me_verifier` varchar(96) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`game_id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`game_id`,`email`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `game_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
