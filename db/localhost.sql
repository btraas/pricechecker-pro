-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 17, 2017 at 02:32 PM
-- Server version: 5.7.16-0ubuntu0.16.04.1
-- PHP Version: 7.0.13-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sslifts`
--
CREATE DATABASE IF NOT EXISTS `sslifts` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sslifts`;

-- --------------------------------------------------------

--
-- Table structure for table `default_equipment_plates`
--

CREATE TABLE `default_equipment_plates` (
  `equipment_id` int(11) NOT NULL,
  `weight_kg` decimal(5,2) NOT NULL DEFAULT '0.00',
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `default_equipment_plates`
--

INSERT INTO `default_equipment_plates` (`equipment_id`, `weight_kg`, `quantity`) VALUES
(1, '1.25', 4),
(1, '2.50', 4),
(1, '5.00', 4),
(1, '10.00', 4),
(1, '20.00', 8);

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `equipment_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `sides` int(11) NOT NULL,
  `base_weight_kg` int(11) NOT NULL DEFAULT '0',
  `alternate_equipment` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`equipment_id`, `name`, `sides`, `base_weight_kg`, `alternate_equipment`) VALUES
(1, 'Barbell', 2, 20, NULL),
(2, 'Dumbell', 1, 0, 5),
(3, 'Bodyweight', 1, 0, NULL),
(4, 'Cable', 1, 0, NULL),
(5, 'Adjustable dumbell', 2, 2, 2),
(6, 'Machine', 1, 0, NULL),
(7, '45&deg; Leg press', 2, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exercise_milestones`
--

CREATE TABLE `exercise_milestones` (
  `exercise_id` int(11) NOT NULL,
  `weight_kg` int(11) NOT NULL,
  `milestone_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exercise_milestones`
--

INSERT INTO `exercise_milestones` (`exercise_id`, `weight_kg`, `milestone_id`) VALUES
(1, 71, 6),
(1, 131, 7),
(1, 151, 8),
(1, 205, 9),
(1, 249, 10),
(2, 57, 6),
(2, 106, 7),
(2, 128, 8),
(2, 175, 9),
(2, 228, 10),
(3, 61, 6),
(3, 77, 7),
(3, 93, 8),
(3, 131, 9),
(3, 164, 10),
(4, 35, 6),
(4, 49, 7),
(4, 63, 8),
(4, 77, 9),
(5, 58, 6),
(5, 71, 7),
(5, 87, 8),
(5, 126, 9),
(8, 55, 6),
(8, 88, 7),
(8, 116, 8),
(8, 153, 9),
(8, 196, 10),
(9, 38, 6),
(9, 74, 7),
(9, 91, 8),
(9, 123, 9),
(10, 33, 6),
(10, 60, 7),
(10, 71, 8),
(10, 98, 9);

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `exercise_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`exercise_id`, `name`, `equipment_id`) VALUES
(1, 'Deadlift', 1),
(2, 'Squat', 1),
(3, 'Bench Press', 1),
(4, 'Overhead Press', 1),
(5, 'Barbell Row', 1),
(6, 'Pullups', 3),
(7, 'Dips', 3),
(8, 'Front Squat', 1),
(9, 'Power Clean', 1),
(10, 'Power Snatch', 1),
(11, 'Pulldowns', 4),
(12, 'Pullups', 3),
(13, 'Chinups', 3),
(14, 'Cable Row', 4),
(15, 'Chest Supported Row', 4),
(16, 'Face Pulls', 4),
(17, 'Hammer Curls', 2),
(18, 'Dumbell Curls', 2),
(19, 'Incline Dumbell Press', 2),
(20, 'Tricep Pushdowns', 4),
(21, 'Lateral Raises', 2),
(22, 'Tricep Extensions', 2),
(23, 'Romanian Deadlift', 1),
(24, 'Leg Press', 7),
(25, 'Leg Curls', 6),
(26, 'Calf Raises', 6);

-- --------------------------------------------------------

--
-- Table structure for table `program_exercises`
--

CREATE TABLE `program_exercises` (
  `program_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `session` int(11) DEFAULT NULL,
  `sets` int(11) DEFAULT NULL,
  `reps_min` int(11) DEFAULT NULL,
  `reps_max` int(11) DEFAULT NULL,
  `last_amrap` tinyint(1) DEFAULT '0',
  `session_progress_kg` decimal(4,2) DEFAULT NULL,
  `week` int(11) DEFAULT NULL,
  `optional` tinyint(1) DEFAULT '0',
  `alternate_exercise` int(11) DEFAULT NULL,
  `alternate_exercise_2` int(11) DEFAULT NULL,
  `program_exercise_id` int(11) NOT NULL,
  `superset_program_exercise_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `program_exercises`
--

INSERT INTO `program_exercises` (`program_id`, `exercise_id`, `session`, `sets`, `reps_min`, `reps_max`, `last_amrap`, `session_progress_kg`, `week`, `optional`, `alternate_exercise`, `alternate_exercise_2`, `program_exercise_id`, `superset_program_exercise_id`) VALUES
(1, 2, 1, 5, 5, 5, 0, '2.50', 1, 0, NULL, NULL, 1, NULL),
(1, 3, 1, 5, 5, 5, 0, '2.50', 1, 0, NULL, NULL, 2, NULL),
(1, 5, 1, 5, 5, 5, 0, '2.50', 1, 0, NULL, NULL, 3, NULL),
(1, 2, 2, 5, 5, 5, 0, '2.50', 1, 0, NULL, NULL, 4, NULL),
(1, 4, 2, 5, 5, 5, 0, '2.50', 1, 0, NULL, NULL, 5, NULL),
(1, 1, 2, 1, 5, 5, 0, '2.50', 1, 0, NULL, NULL, 6, NULL),
(1, 2, 3, 5, 5, 5, 0, '2.50', 1, 0, NULL, NULL, 7, NULL),
(1, 3, 3, 5, 5, 5, 0, '2.50', 1, 0, NULL, NULL, 8, NULL),
(1, 5, 3, 5, 5, 5, 0, '2.50', 1, 0, NULL, NULL, 9, NULL),
(1, 2, 1, 5, 5, 5, 0, '2.50', 2, 0, NULL, NULL, 10, NULL),
(1, 4, 1, 5, 5, 5, 0, '2.50', 2, 0, NULL, NULL, 11, NULL),
(1, 1, 1, 1, 5, 5, 0, '2.50', 2, 0, NULL, NULL, 12, NULL),
(1, 2, 2, 5, 5, 5, 0, '2.50', 2, 0, NULL, NULL, 13, NULL),
(1, 3, 2, 5, 5, 5, 0, '2.50', 2, 0, NULL, NULL, 14, NULL),
(1, 5, 2, 5, 5, 5, 0, '2.50', 2, 0, NULL, NULL, 15, NULL),
(1, 2, 3, 5, 5, 5, 0, '2.50', 2, 0, NULL, NULL, 16, NULL),
(1, 4, 3, 5, 5, 5, 0, '2.50', 2, 0, NULL, NULL, 17, NULL),
(1, 1, 3, 1, 5, 5, 0, '2.50', 2, 0, NULL, NULL, 18, NULL),
(2, 1, 1, 1, 5, 5, 1, '5.00', 1, 0, NULL, NULL, 19, NULL),
(2, 11, 1, 3, 8, 12, 0, '2.50', 1, 0, 12, 13, 20, NULL),
(2, 14, 1, 3, 8, 12, 0, '2.50', 1, 0, 15, NULL, 21, NULL),
(2, 16, 1, 5, 15, 20, 0, '2.50', 1, 0, NULL, NULL, 22, NULL),
(2, 17, 1, 4, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 23, NULL),
(2, 18, 1, 4, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 24, NULL),
(2, 3, 2, 5, 5, 5, 1, '2.50', 1, 0, NULL, NULL, 25, NULL),
(2, 4, 2, 3, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 26, NULL),
(2, 19, 2, 3, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 27, NULL),
(2, 20, 2, 3, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 28, 29),
(2, 21, 2, 3, 15, 20, 0, '2.50', 1, 0, NULL, NULL, 29, NULL),
(2, 22, 2, 3, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 30, 31),
(2, 21, 2, 3, 15, 20, 0, '2.50', 1, 0, NULL, NULL, 31, NULL),
(2, 2, 3, 3, 5, 5, 1, '2.50', 1, 0, NULL, NULL, 32, NULL),
(2, 23, 3, 3, 8, 12, 0, '5.00', 1, 0, NULL, NULL, 33, NULL),
(2, 24, 3, 3, 8, 12, 0, '5.00', 1, 0, NULL, NULL, 34, NULL),
(2, 25, 3, 3, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 35, NULL),
(2, 26, 3, 5, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 36, NULL),
(2, 5, 4, 5, 5, 5, 1, '2.50', 1, 0, NULL, NULL, 37, NULL),
(2, 11, 4, 3, 8, 12, 0, '2.50', 1, 0, 12, 13, 38, NULL),
(2, 14, 4, 3, 8, 12, 0, '2.50', 1, 0, 15, NULL, 39, NULL),
(2, 16, 4, 5, 15, 20, 0, '2.50', 1, 0, NULL, NULL, 40, NULL),
(2, 17, 4, 4, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 41, NULL),
(2, 18, 4, 4, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 42, NULL),
(2, 4, 5, 5, 5, 5, 1, '2.50', 1, 0, NULL, NULL, 43, NULL),
(2, 3, 5, 3, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 44, NULL),
(2, 19, 5, 3, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 45, NULL),
(2, 20, 5, 3, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 46, NULL),
(2, 21, 5, 3, 15, 20, 0, '2.50', 1, 0, NULL, NULL, 47, 46),
(2, 22, 5, 3, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 48, NULL),
(2, 21, 5, 3, 15, 20, 0, '2.50', 1, 0, NULL, NULL, 49, 48),
(2, 2, 6, 3, 5, 5, 1, '2.50', 1, 0, NULL, NULL, 50, NULL),
(2, 23, 6, 3, 8, 12, 0, '5.00', 1, 0, NULL, NULL, 51, NULL),
(2, 24, 6, 3, 8, 12, 0, '5.00', 1, 0, NULL, NULL, 52, NULL),
(2, 25, 6, 3, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 53, NULL),
(2, 26, 6, 5, 8, 12, 0, '2.50', 1, 0, NULL, NULL, 54, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `program_session`
--

CREATE TABLE `program_session` (
  `program_id` int(11) NOT NULL,
  `session` int(11) NOT NULL,
  `week` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `program_session`
--

INSERT INTO `program_session` (`program_id`, `session`, `week`, `name`) VALUES
(1, 1, 1, 'Squat & Bench Press'),
(1, 1, 2, 'Squat & Overhead Press'),
(1, 2, 1, 'Squat & Overhead Press'),
(1, 2, 2, 'Squat & Bench Press'),
(1, 3, 1, 'Squat & Bench Press'),
(1, 3, 2, 'Squat & Overhead Press'),
(2, 1, 1, 'Deadlift & Pulldowns'),
(2, 2, 1, 'Bench Press & Overhead Press'),
(2, 3, 1, 'Squat & Romanian Deadlift'),
(2, 4, 1, 'Barbell Row & Pulldowns'),
(2, 5, 1, 'Overhead Press & Bench Press'),
(2, 6, 1, 'Squat & Romanian Deadlift');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_short` varchar(25) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `private_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `name`, `name_short`, `description`, `private_user_id`) VALUES
(1, 'Stronglifts 5x5', 'Stronglifts', 'The simplest, most effective workout to get stronger, build muscle and burn fat.', NULL),
(2, 'Metallicadpa\'s PPL Program for Beginners', 'Metallicadpa\'s PPL', 'A Linear Progression Based PPL Program for Beginners', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `programs_view`
--
CREATE TABLE `programs_view` (
`program_id` int(11)
,`name` varchar(100)
,`description` varchar(1000)
,`private_user_id` int(11)
,`exercises` text
);

-- --------------------------------------------------------

--
-- Table structure for table `user_program`
--

CREATE TABLE `user_program` (
  `user_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `session` int(11) DEFAULT NULL,
  `sets` int(11) DEFAULT NULL,
  `reps_min` int(11) DEFAULT NULL,
  `reps_max` int(11) DEFAULT NULL,
  `last_amrap` tinyint(1) DEFAULT '0',
  `session_progress_kg` decimal(4,2) DEFAULT NULL,
  `week` int(11) DEFAULT NULL,
  `optional` tinyint(1) DEFAULT '1',
  `alternate_exercise` int(11) DEFAULT NULL,
  `alternate_exercise_2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `user_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `sets` int(11) DEFAULT NULL,
  `reps` int(11) NOT NULL,
  `weight_kg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_progress`
--

INSERT INTO `user_progress` (`user_id`, `exercise_id`, `date`, `sets`, `reps`, `weight_kg`) VALUES
(1, 1, '2016-01-01', 5, 5, 200),
(1, 1, '2016-01-15', 5, 5, 220),
(1, 1, '2016-01-30', 5, 5, 230),
(1, 1, '2016-03-05', 5, 5, 265);

-- --------------------------------------------------------

--
-- Stand-in structure for view `user_progress_view`
--
CREATE TABLE `user_progress_view` (
`user_id` int(11)
,`exercise_id` int(11)
,`date` date
,`sets` int(11)
,`reps` int(11)
,`weight_kg` int(11)
,`name` varchar(40)
,`exercise` varchar(50)
,`weight` double
,`units` varchar(5)
);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `sex` char(1) DEFAULT NULL,
  `bodyweight_kg` decimal(6,2) DEFAULT NULL,
  `weight_unit_id` int(11) NOT NULL DEFAULT '1',
  `created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_accessed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `picture` varchar(200) DEFAULT NULL,
  `locale` varchar(5) DEFAULT NULL,
  `current_program` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `name`, `sex`, `bodyweight_kg`, `weight_unit_id`, `created_timestamp`, `modified_timestamp`, `last_accessed`, `picture`, `locale`, `current_program`) VALUES
(1, 'btraas@gmail.com', 'Brayden Traas', 'm', '91.00', 2, '2016-12-03 08:29:37', '2017-01-17 05:23:54', '2017-01-17 05:23:54', 'https://lh4.googleusercontent.com/-LBuEFgz0iwU/AAAAAAAAAAI/AAAAAAAAEwU/AIdCfoKeduM/s96-c/photo.jpg', 'en', 2),
(2, 'brayden@tra.as', 'Brayden Traas', NULL, NULL, 2, '2016-12-03 21:26:08', '2016-12-03 21:26:08', '2016-12-03 21:26:08', 'https://lh5.googleusercontent.com/-xYH2YpKL7Bs/AAAAAAAAAAI/AAAAAAAAAAA/AEMOYSBgv1IWPkl7m8htzjrq3JuovaMsfw/s96-c/photo.jpg', 'en', 2);

-- --------------------------------------------------------

--
-- Stand-in structure for view `users_view`
--
CREATE TABLE `users_view` (
`user_id` int(11)
,`email` varchar(30)
,`name` varchar(40)
,`sex` char(1)
,`bodyweight_kg` decimal(6,2)
,`weight_unit_id` int(11)
,`created_timestamp` timestamp
,`modified_timestamp` timestamp
,`last_accessed` timestamp
,`picture` varchar(200)
,`locale` varchar(5)
,`unit_name` varchar(20)
,`amount_per_kg` double
,`units` varchar(5)
,`current_program` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `weight_standards`
--

CREATE TABLE `weight_standards` (
  `standard_id` int(11) NOT NULL,
  `name` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `weight_standards`
--

INSERT INTO `weight_standards` (`standard_id`, `name`) VALUES
(1, 'Strength Standards');

-- --------------------------------------------------------

--
-- Table structure for table `weight_standards_milestones`
--

CREATE TABLE `weight_standards_milestones` (
  `milestone_id` int(11) NOT NULL,
  `standard_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `weight_standards_milestones`
--

INSERT INTO `weight_standards_milestones` (`milestone_id`, `standard_id`, `name`, `description`) VALUES
(6, 1, 'Untrained', 'Untrained refers to the expected level of strength in a healthy individual who has not trained on the exercise before but can perform it correctly. This represents the minimum level of strength required to maintain a reasonable quality of life in a sedentary individual.'),
(7, 1, 'Novice', 'Novice means a person training regularly for a period of 3-9 months. This strength level supports the demands of vigorous recreational activities.'),
(8, 1, 'Intermediate', 'Intermediate is a person who has engaged in regular training for up to two years. The intermediate level indicates some degree of specialization in the exercises and a high level of performance at the recreational level.'),
(9, 1, 'Advanced', 'Advanced refers to an individual with multi-year training experience with definite goals in the higher levels of competitive athletics.'),
(10, 1, 'Elite', 'Elite refers specifically to athletes competing in strength sports. Less than 1% of the weight training population will attain this level.');

-- --------------------------------------------------------

--
-- Table structure for table `weight_units`
--

CREATE TABLE `weight_units` (
  `weight_unit_id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `identifier` varchar(5) DEFAULT NULL,
  `amount_per_kg` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `weight_units`
--

INSERT INTO `weight_units` (`weight_unit_id`, `name`, `identifier`, `amount_per_kg`) VALUES
(1, 'kilogram', 'kg', 1),
(2, 'pound', 'lb', 2.20462);

-- --------------------------------------------------------

--
-- Structure for view `programs_view`
--
DROP TABLE IF EXISTS `programs_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sslifts`@`localhost` SQL SECURITY DEFINER VIEW `programs_view`  AS  (select `p`.`program_id` AS `program_id`,`p`.`name` AS `name`,`p`.`description` AS `description`,`p`.`private_user_id` AS `private_user_id`,group_concat(distinct `e`.`name` separator ',') AS `exercises` from ((`programs` `p` join `program_exercises` `pe` on((`pe`.`program_id` = `p`.`program_id`))) join `exercises` `e` on((`e`.`exercise_id` = `pe`.`exercise_id`))) group by `p`.`program_id`) ;

-- --------------------------------------------------------

--
-- Structure for view `user_progress_view`
--
DROP TABLE IF EXISTS `user_progress_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_progress_view`  AS  (select `up`.`user_id` AS `user_id`,`up`.`exercise_id` AS `exercise_id`,`up`.`date` AS `date`,`up`.`sets` AS `sets`,`up`.`reps` AS `reps`,`up`.`weight_kg` AS `weight_kg`,`u`.`name` AS `name`,`ex`.`name` AS `exercise`,(`up`.`weight_kg` * `wu`.`amount_per_kg`) AS `weight`,`wu`.`identifier` AS `units` from (((`user_progress` `up` join `exercises` `ex` on((`ex`.`exercise_id` = `up`.`exercise_id`))) join `users` `u` on((`u`.`user_id` = `up`.`user_id`))) join `weight_units` `wu` on((`wu`.`weight_unit_id` = `u`.`weight_unit_id`)))) ;

-- --------------------------------------------------------

--
-- Structure for view `users_view`
--
DROP TABLE IF EXISTS `users_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `users_view`  AS  (select `u`.`user_id` AS `user_id`,`u`.`email` AS `email`,`u`.`name` AS `name`,`u`.`sex` AS `sex`,`u`.`bodyweight_kg` AS `bodyweight_kg`,`u`.`weight_unit_id` AS `weight_unit_id`,`u`.`created_timestamp` AS `created_timestamp`,`u`.`modified_timestamp` AS `modified_timestamp`,`u`.`last_accessed` AS `last_accessed`,`u`.`picture` AS `picture`,`u`.`locale` AS `locale`,`wu`.`name` AS `unit_name`,`wu`.`amount_per_kg` AS `amount_per_kg`,`wu`.`identifier` AS `units`,`u`.`current_program` AS `current_program` from (`users` `u` join `weight_units` `wu` on((`wu`.`weight_unit_id` = `u`.`weight_unit_id`)))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `default_equipment_plates`
--
ALTER TABLE `default_equipment_plates`
  ADD PRIMARY KEY (`equipment_id`,`weight_kg`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`equipment_id`),
  ADD KEY `alternate_equipment` (`alternate_equipment`);

--
-- Indexes for table `exercise_milestones`
--
ALTER TABLE `exercise_milestones`
  ADD PRIMARY KEY (`exercise_id`,`milestone_id`),
  ADD KEY `milestone_id` (`milestone_id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`exercise_id`);

--
-- Indexes for table `program_exercises`
--
ALTER TABLE `program_exercises`
  ADD PRIMARY KEY (`program_exercise_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `exercise_id` (`exercise_id`),
  ADD KEY `alternate_exercise` (`alternate_exercise`),
  ADD KEY `alternate_exercise_2` (`alternate_exercise_2`),
  ADD KEY `superset_program_exercise_id` (`superset_program_exercise_id`);

--
-- Indexes for table `program_session`
--
ALTER TABLE `program_session`
  ADD PRIMARY KEY (`program_id`,`session`,`week`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`),
  ADD KEY `private_user_id` (`private_user_id`);

--
-- Indexes for table `user_program`
--
ALTER TABLE `user_program`
  ADD KEY `exercise_id` (`exercise_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`user_id`,`exercise_id`,`date`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `weight_unit_id_fk` (`weight_unit_id`),
  ADD KEY `email` (`email`),
  ADD KEY `current_program` (`current_program`);

--
-- Indexes for table `weight_standards`
--
ALTER TABLE `weight_standards`
  ADD PRIMARY KEY (`standard_id`);

--
-- Indexes for table `weight_standards_milestones`
--
ALTER TABLE `weight_standards_milestones`
  ADD PRIMARY KEY (`milestone_id`),
  ADD KEY `standard_id` (`standard_id`);

--
-- Indexes for table `weight_units`
--
ALTER TABLE `weight_units`
  ADD PRIMARY KEY (`weight_unit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `equipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `exercise_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `program_exercises`
--
ALTER TABLE `program_exercises`
  MODIFY `program_exercise_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `weight_standards`
--
ALTER TABLE `weight_standards`
  MODIFY `standard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `weight_standards_milestones`
--
ALTER TABLE `weight_standards_milestones`
  MODIFY `milestone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `weight_units`
--
ALTER TABLE `weight_units`
  MODIFY `weight_unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `default_equipment_plates`
--
ALTER TABLE `default_equipment_plates`
  ADD CONSTRAINT `equipment_id_fk` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`);

--
-- Constraints for table `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`alternate_equipment`) REFERENCES `equipment` (`equipment_id`);

--
-- Constraints for table `exercise_milestones`
--
ALTER TABLE `exercise_milestones`
  ADD CONSTRAINT `exercise_milestones_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`exercise_id`);

--
-- Constraints for table `program_exercises`
--
ALTER TABLE `program_exercises`
  ADD CONSTRAINT `program_exercises_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`program_id`),
  ADD CONSTRAINT `program_exercises_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`exercise_id`),
  ADD CONSTRAINT `program_exercises_ibfk_3` FOREIGN KEY (`alternate_exercise`) REFERENCES `exercises` (`exercise_id`),
  ADD CONSTRAINT `program_exercises_ibfk_4` FOREIGN KEY (`alternate_exercise_2`) REFERENCES `exercises` (`exercise_id`),
  ADD CONSTRAINT `program_exercises_ibfk_5` FOREIGN KEY (`superset_program_exercise_id`) REFERENCES `program_exercises` (`program_exercise_id`);

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_ibfk_1` FOREIGN KEY (`private_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_program`
--
ALTER TABLE `user_program`
  ADD CONSTRAINT `user_program_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`exercise_id`),
  ADD CONSTRAINT `user_program_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`exercise_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`current_program`) REFERENCES `programs` (`program_id`),
  ADD CONSTRAINT `weight_unit_id_fk` FOREIGN KEY (`weight_unit_id`) REFERENCES `weight_units` (`weight_unit_id`);

--
-- Constraints for table `weight_standards_milestones`
--
ALTER TABLE `weight_standards_milestones`
  ADD CONSTRAINT `weight_standards_milestones_ibfk_1` FOREIGN KEY (`standard_id`) REFERENCES `weight_standards` (`standard_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
