-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 15, 2024 at 11:46 PM
-- Server version: 5.7.44
-- PHP Version: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `twilcher_endless`
--

-- --------------------------------------------------------

--
-- Table structure for table `artworks`
--

CREATE TABLE `artworks` (
  `piece_id` int(11) NOT NULL,
  `piece_name` varchar(255) NOT NULL,
  `piece_year` int(11) DEFAULT NULL,
  `piece_description` text,
  `medium_type` varchar(100) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `web_filename` varchar(255) DEFAULT NULL,
  `full_filename` varchar(255) DEFAULT NULL,
  `image_filename` varchar(255) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `artworks`
--

INSERT INTO `artworks` (`piece_id`, `piece_name`, `piece_year`, `piece_description`, `medium_type`, `filename`, `web_filename`, `full_filename`, `image_filename`, `person_id`) VALUES
(1, 'Self Portrait', 1889, NULL, 'oil on canvas', '1SelfPortrait.png', NULL, NULL, 'Images/Van Gogh/Self Portrait.jpg', 1),
(2, 'Skull With Cigarette', 1886, NULL, 'oil on canvas', '2SkullWithCig.png', NULL, NULL, 'Images/Van Gogh/Skull With Cigarette.jpg', 1),
(3, 'Starry Night', 1889, NULL, 'oil on canvas', '3StarryNight.png', NULL, NULL, 'Images/Van Gogh/Starry Night.jpg', 1),
(4, 'Starry Night over the Rhone', 1888, NULL, 'oil on canvas', '4StarryNightRhone.png', NULL, NULL, 'Images/Van Gogh/Starry Night over the Rhone.jpg', 1),
(5, 'Two Poplars on a Hill', 1889, NULL, 'oil on canvas', '5TwoPoplarHill.png', NULL, NULL, 'Images/Van Gogh/Two Poplars on a Hill.jpg', 1),
(6, 'Vineyards at Auvers', 1890, NULL, 'oil on canvas', '6VineyardsAuvers.png', NULL, NULL, 'Images/Van Gogh/Vineyards at Auvers.jpg', 1),
(7, 'Stairway at Auvers', 1890, NULL, 'oil on canvas', '7StairwayAuvers.png', NULL, NULL, 'Images/Van Gogh/Stairway at Auvers.jpg', 1),
(8, 'Dutch Irises', 1890, NULL, 'oil on canvas', '8DutchIrises.png', NULL, NULL, 'Images/Van Gogh/Dutch Irises.jpg', 1),
(9, 'Garden of Dr. Gachet', 1890, NULL, 'oil on canvas', '9GardenGachet.png', NULL, NULL, 'Images/Van Gogh/Garden of Dr. Gachet.jpg', 1),
(10, 'First Steps', 1890, NULL, 'oil on canvas', '10FirstSteps.png', NULL, NULL, 'Images/Van Gogh/First Steps.jpg', 1),
(11, 'Prison Courtyard', 1890, NULL, 'oil on canvas', '11PrisonCourtyard.png', NULL, NULL, 'Images/Van Gogh/Prison Courtyard.jpg', 1),
(12, 'Cafe Terrace at Night', 1888, NULL, 'oil on canvas', '12CafeTerraceNight.png', NULL, NULL, 'Images/Van Gogh/Cafe Terrace at Night.jpg', 1),
(13, 'Roadway with Underpass', 1887, NULL, 'oil on canvas', '13RoadwayUnderpass.png', NULL, NULL, 'Images/Van Gogh/Roadway with Underpass.jpg', 1),
(14, 'Landscape with Figures', 1889, NULL, 'oil on canvas', '14LandscapeFigures.png', NULL, NULL, 'Images/Van Gogh/Landscape with Figures.jpg', 1),
(15, 'The Night Café', 1888, NULL, 'oil on canvas', '15NightCafe.png', NULL, NULL, 'Images/Van Gogh/The Night Café.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `artworks_x_keywords`
--

CREATE TABLE `artworks_x_keywords` (
  `piece_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `artworks_x_keywords`
--

INSERT INTO `artworks_x_keywords` (`piece_id`, `keyword_id`) VALUES
(3, 1),
(3, 3),
(3, 13),
(3, 15),
(1, 16),
(2, 16),
(3, 16),
(1, 17),
(1, 18),
(1, 21),
(2, 28),
(3, 29),
(1, 30),
(3, 30),
(2, 31),
(2, 34);

-- --------------------------------------------------------

--
-- Table structure for table `artworks_x_people`
--

CREATE TABLE `artworks_x_people` (
  `piece_id` int(11) NOT NULL,
  `peopleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `artwork_reviews`
--

CREATE TABLE `artwork_reviews` (
  `id` int(11) NOT NULL,
  `piece_id` int(11) DEFAULT NULL,
  `peopleID` int(11) DEFAULT NULL,
  `comment_title` varchar(100) DEFAULT NULL,
  `comment` text,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `ID` int(11) NOT NULL,
  `user_ID` int(11) DEFAULT NULL,
  `piece_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

CREATE TABLE `keywords` (
  `keyword_id` int(11) NOT NULL,
  `keyword_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `keywords`
--

INSERT INTO `keywords` (`keyword_id`, `keyword_name`) VALUES
(22, 'architecture'),
(32, 'atmospheric'),
(24, 'auvers'),
(15, 'blue'),
(18, 'brushwork'),
(7, 'buildings'),
(19, 'color contrast'),
(28, 'dark tones'),
(30, 'dramatic'),
(23, 'figures'),
(5, 'flowers'),
(25, 'france'),
(27, 'green'),
(17, 'impasto'),
(10, 'interior'),
(3, 'landscape'),
(31, 'melancholy'),
(34, 'mysterious'),
(4, 'nature'),
(1, 'night scene'),
(20, 'perspective'),
(21, 'portrait'),
(16, 'post-impressionism'),
(26, 'provence'),
(8, 'rural life'),
(6, 'self-portrait'),
(33, 'serene'),
(13, 'sky'),
(2, 'stars'),
(9, 'still life'),
(11, 'trees'),
(29, 'vibrant colors'),
(12, 'village'),
(14, 'yellow');

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `PID` int(11) NOT NULL,
  `author` enum('y','n') DEFAULT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `pw` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`PID`, `author`, `fname`, `lname`, `username`, `pw`) VALUES
(1, 'y', 'Vincent', 'van Gogh', 'vvangogh', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artworks`
--
ALTER TABLE `artworks`
  ADD PRIMARY KEY (`piece_id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `artworks_x_keywords`
--
ALTER TABLE `artworks_x_keywords`
  ADD PRIMARY KEY (`piece_id`,`keyword_id`),
  ADD KEY `keyword_id` (`keyword_id`);

--
-- Indexes for table `artworks_x_people`
--
ALTER TABLE `artworks_x_people`
  ADD PRIMARY KEY (`piece_id`,`peopleID`),
  ADD KEY `peopleID` (`peopleID`);

--
-- Indexes for table `artwork_reviews`
--
ALTER TABLE `artwork_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `piece_id` (`piece_id`),
  ADD KEY `peopleID` (`peopleID`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `piece_id` (`piece_id`);

--
-- Indexes for table `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`keyword_id`),
  ADD UNIQUE KEY `keyword_name` (`keyword_name`);

--
-- Indexes for table `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`PID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artworks`
--
ALTER TABLE `artworks`
  MODIFY `piece_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `artwork_reviews`
--
ALTER TABLE `artwork_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keywords`
--
ALTER TABLE `keywords`
  MODIFY `keyword_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `people`
--
ALTER TABLE `people`
  MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artworks`
--
ALTER TABLE `artworks`
  ADD CONSTRAINT `artworks_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `people` (`PID`);

--
-- Constraints for table `artworks_x_keywords`
--
ALTER TABLE `artworks_x_keywords`
  ADD CONSTRAINT `artworks_x_keywords_ibfk_1` FOREIGN KEY (`piece_id`) REFERENCES `artworks` (`piece_id`),
  ADD CONSTRAINT `artworks_x_keywords_ibfk_2` FOREIGN KEY (`keyword_id`) REFERENCES `keywords` (`keyword_id`);

--
-- Constraints for table `artworks_x_people`
--
ALTER TABLE `artworks_x_people`
  ADD CONSTRAINT `artworks_x_people_ibfk_1` FOREIGN KEY (`piece_id`) REFERENCES `artworks` (`piece_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `artworks_x_people_ibfk_2` FOREIGN KEY (`peopleID`) REFERENCES `people` (`PID`) ON DELETE CASCADE;

--
-- Constraints for table `artwork_reviews`
--
ALTER TABLE `artwork_reviews`
  ADD CONSTRAINT `artwork_reviews_ibfk_1` FOREIGN KEY (`piece_id`) REFERENCES `artworks` (`piece_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `artwork_reviews_ibfk_2` FOREIGN KEY (`peopleID`) REFERENCES `people` (`PID`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `people` (`PID`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`piece_id`) REFERENCES `artworks` (`piece_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
