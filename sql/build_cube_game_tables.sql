SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `cube_game`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` varchar(55) DEFAULT NULL,
  `active` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `description`, `active`) VALUES
(1, 'A somber room with another interesting smell.', 1),
(2, 'Another room with an empty table.', 1),
(3, 'Another nice room to pass through.', 1),
(4, 'A good room to start an adventure from....', 1),
(5, 'You win! Help youself to refreshments on the table.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `commands`
--

CREATE TABLE `commands` (
  `id` int(11) UNSIGNED NOT NULL,
  `command` varchar(25) DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commands`
--

INSERT INTO `commands` (`id`, `command`, `active`) VALUES
(1, 'north', 1),
(2, 'south', 1),
(3, 'east', 1),
(4, 'west', 1),
(5, 'up', 1),
(6, 'down', 1),
(7, 'tell', 1),
(8, 'say', 1),
(9, 'yell', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cubes`
--

CREATE TABLE `cubes` (
  `id` int(11) UNSIGNED NOT NULL,
  `north_id` int(11) UNSIGNED DEFAULT NULL,
  `south_id` int(11) UNSIGNED DEFAULT NULL,
  `east_id` int(11) UNSIGNED DEFAULT NULL,
  `west_id` int(11) UNSIGNED DEFAULT NULL,
  `up_id` int(11) UNSIGNED DEFAULT NULL,
  `down_id` int(11) UNSIGNED DEFAULT NULL,
  `default_level` int(11) UNSIGNED DEFAULT NULL,
  `default_attribute` int(11) UNSIGNED DEFAULT NULL,
  `entrance` varchar(15) DEFAULT NULL,
  `screen_name` varchar(15) DEFAULT NULL,
  `start_game_room` tinyint(1) UNSIGNED DEFAULT NULL,
  `solid` tinyint(1) UNSIGNED DEFAULT NULL,
  `win_here` tinyint(1) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cubes`
--

INSERT INTO `cubes` (`id`, `north_id`, `south_id`, `east_id`, `west_id`, `up_id`, `down_id`, `default_level`, `default_attribute`, `entrance`, `screen_name`, `start_game_room`, `solid`, `win_here`, `active`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, 11, NULL, NULL, 'down', 'solid room', 0, 1, 0, 1),
(2, NULL, NULL, 11, NULL, NULL, NULL, NULL, NULL, 'east', 'solid room', 0, 1, 0, 1),
(3, NULL, NULL, NULL, NULL, 11, NULL, NULL, NULL, 'up', 'solid room', 0, 1, 0, 1),
(4, NULL, 11, NULL, NULL, NULL, NULL, NULL, NULL, 'south', 'solid room', 0, 1, 0, 1),
(5, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'north', 'solid room', 0, 1, 0, 1),
(6, NULL, 10, NULL, NULL, NULL, NULL, NULL, NULL, 'south', 'solid room', 0, 1, 0, 1),
(7, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'north', 'solid room', 0, 1, 0, 1),
(8, NULL, NULL, NULL, 10, NULL, NULL, NULL, NULL, 'west', 'solid room', 0, 1, 0, 1),
(9, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL, 'up', 'solid room', 0, 1, 0, 1),
(10, 6, 7, 8, 11, 12, 9, NULL, NULL, NULL, 'clear room', 0, 0, 0, 1),
(11, 4, 5, 10, 2, 1, 3, 5, NULL, NULL, 'start_here', 1, 0, 0, 1),
(12, NULL, NULL, NULL, NULL, NULL, 10, NULL, NULL, 'down', 'The Finish', 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cube_attributes`
--

CREATE TABLE `cube_attributes` (
  `id` int(11) UNSIGNED NOT NULL,
  `attribute_id` int(11) UNSIGNED DEFAULT NULL,
  `cube_id` int(10) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cube_attributes`
--

INSERT INTO `cube_attributes` (`id`, `attribute_id`, `cube_id`, `active`) VALUES
(6, 1, 1, 1),
(7, 2, 2, 1),
(8, 2, 3, 1),
(9, 2, 4, 1),
(10, 2, 5, 1),
(11, 2, 6, 1),
(12, 2, 7, 1),
(13, 2, 8, 1),
(14, 2, 9, 1),
(15, 3, 10, 1),
(16, 4, 11, 1),
(17, 5, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cube_commands`
--

CREATE TABLE `cube_commands` (
  `id` int(11) UNSIGNED NOT NULL,
  `command_id` int(11) UNSIGNED DEFAULT NULL,
  `game_id` int(11) UNSIGNED DEFAULT NULL,
  `level_id` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cube_game_moves`
--

CREATE TABLE `cube_game_moves` (
  `id` int(11) UNSIGNED NOT NULL,
  `game_id` int(11) UNSIGNED DEFAULT NULL,
  `cube_world_id` int(11) UNSIGNED DEFAULT NULL,
  `cube_id` int(11) UNSIGNED DEFAULT NULL,
  `level_id` int(11) UNSIGNED DEFAULT NULL,
  `player_id` int(11) UNSIGNED DEFAULT NULL,
  `command` varchar(25) DEFAULT NULL,
  `winner` tinyint(1) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cube_game_moves`
--

INSERT INTO `cube_game_moves` (`id`, `game_id`, `cube_world_id`, `cube_id`, `level_id`, `player_id`, `command`, `winner`, `active`, `updated`) VALUES
(2, 1, 1, 11, NULL, 1, 'start', 1, 1, '2016-10-27 09:32:55'),
(14, 1, 1, 1, NULL, 2, 'start', NULL, 1, '2016-10-26 13:25:33'),
(15, 1, 1, 2, NULL, 3, 'start', NULL, 1, '2016-10-26 13:25:46'),
(16, 1, 1, 3, NULL, 4, 'start', NULL, 1, '2016-10-26 13:25:56'),
(17, 1, 1, 5, NULL, 5, 'start', NULL, 1, '2016-10-26 13:26:07'),
(18, 1, 1, 6, NULL, 7, 'start', NULL, 1, '2016-10-26 13:26:23'),
(19, 1, 1, 7, NULL, 8, 'start', NULL, 1, '2016-10-26 13:26:37'),
(20, 1, 1, 8, NULL, 9, 'start', NULL, 1, '2016-10-26 13:26:49'),
(21, 1, 1, 9, NULL, 10, 'start', NULL, 1, '2016-10-26 13:27:02');

-- --------------------------------------------------------

--
-- Table structure for table `cube_monsters`
--

CREATE TABLE `cube_monsters` (
  `id` int(11) UNSIGNED NOT NULL,
  `monster_id` int(11) UNSIGNED DEFAULT NULL,
  `cube_id` int(11) UNSIGNED DEFAULT NULL,
  `cube_world_id` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cube_monsters`
--

INSERT INTO `cube_monsters` (`id`, `monster_id`, `cube_id`, `cube_world_id`, `active`) VALUES
(1, 1, 1, 1, 1),
(2, 2, 9, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cube_worlds`
--

CREATE TABLE `cube_worlds` (
  `id` int(11) UNSIGNED NOT NULL,
  `screen_name` varchar(25) DEFAULT NULL,
  `active` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cube_worlds`
--

INSERT INTO `cube_worlds` (`id`, `screen_name`, `active`) VALUES
(1, 'test_world', 1);

-- --------------------------------------------------------

--
-- Table structure for table `error_codes`
--

CREATE TABLE `error_codes` (
  `id` int(11) UNSIGNED NOT NULL,
  `error_code` int(3) UNSIGNED DEFAULT NULL,
  `error_description` varchar(50) DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `error_codes`
--

INSERT INTO `error_codes` (`id`, `error_code`, `error_description`, `active`) VALUES
(1, 123, 'database_error_insert', 1),
(2, 124, 'database_error_select', 1),
(3, 125, 'database_error_update', 1),
(4, 126, 'database_error_unknown', 1),
(5, 127, 'weird_or_unknown_check_php_log', 1);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) UNSIGNED NOT NULL,
  `screen_name` varchar(25) DEFAULT NULL,
  `winner_player_id` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `screen_name`, `winner_player_id`, `active`) VALUES
(1, 'test_game', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `game_move_messages`
--

CREATE TABLE `game_move_messages` (
  `id` int(11) UNSIGNED NOT NULL,
  `north_wall` varchar(50) DEFAULT NULL,
  `south_wall` varchar(50) DEFAULT NULL,
  `east_wall` varchar(50) DEFAULT NULL,
  `west_wall` varchar(50) DEFAULT NULL,
  `up_wall` varchar(50) DEFAULT NULL,
  `down_wall` varchar(50) DEFAULT NULL,
  `north_room` varchar(50) DEFAULT NULL,
  `south_room` varchar(50) DEFAULT NULL,
  `east_room` varchar(50) DEFAULT NULL,
  `west_room` varchar(50) DEFAULT NULL,
  `up_room` varchar(50) DEFAULT NULL,
  `down_room` varchar(50) DEFAULT NULL,
  `active` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game_move_messages`
--

INSERT INTO `game_move_messages` (`id`, `north_wall`, `south_wall`, `east_wall`, `west_wall`, `up_wall`, `down_wall`, `north_room`, `south_room`, `east_room`, `west_room`, `up_room`, `down_room`, `active`) VALUES
(1, 'You cannot pass through the north wall.', 'You cannot pass through the south wall.', 'You cannot pass through the east wall.', 'You cannot pass through the west wall.', 'You cannot fly through the ceiling.', 'You cannot dig your way through the floor.', 'You moved north and entered a room.', 'You moved south and entered a room.', 'You moved east and entered a room.', 'You moved west and entered a room.', 'You moved up and entered a room.', 'You moved down and entered a room.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` int(11) UNSIGNED NOT NULL,
  `win_game` tinyint(1) UNSIGNED DEFAULT NULL,
  `screen_name` varchar(25) DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `win_game`, `screen_name`, `active`) VALUES
(1, 0, 'Main Level', 1),
(2, 0, 'Upper Level 1', 1),
(3, 1, 'Upper Level 2', 1),
(4, 0, 'Lower Level 1', 1),
(5, 0, 'start_level', 1),
(6, 1, 'one_level_up', 1),
(7, 0, 'one_level_down', 1);

-- --------------------------------------------------------

--
-- Table structure for table `monsters`
--

CREATE TABLE `monsters` (
  `id` int(11) UNSIGNED NOT NULL,
  `screen_name` varchar(15) DEFAULT NULL,
  `evil` tinyint(1) UNSIGNED DEFAULT NULL,
  `default_greeting` varchar(50) DEFAULT NULL,
  `default_message` varchar(50) DEFAULT NULL,
  `default_farewell` varchar(50) DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `monsters`
--

INSERT INTO `monsters` (`id`, `screen_name`, `evil`, `default_greeting`, `default_message`, `default_farewell`, `active`) VALUES
(1, 'Freddie', 1, 'Hello kiddy! Wanna play?', 'I\'m going to scratch you deep!', 'Wait! Why are you leaving in one piece?', 1),
(2, 'Lou Cipher', 0, 'Welcome to the underworld.', 'Do you like treats and tasty things?', 'Why are you leaving? It\'s so fun here!', 1);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int(10) UNSIGNED NOT NULL,
  `screen_name` varchar(15) DEFAULT NULL,
  `push_message_ip` varchar(25) NOT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `screen_name`, `push_message_ip`, `active`) VALUES
(1, 'test_player', '127000', 1),
(2, 'test_player_2', '127000', 1),
(3, 'test_player_3', '127000', 1),
(4, 'test_player_4', '127000', 1),
(5, 'test_player_5', '127000', 1),
(6, 'test_player_6', '127000', 1),
(7, 'test_player_7', '127000', 1),
(8, 'test_player_8', '127000', 1),
(9, 'test_player_9', '127000', 1),
(10, 'test_player_10', '127000', 1),
(11, 'test_player_11', '127000', 1),
(12, 'test_player_12', '127000', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commands`
--
ALTER TABLE `commands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cubes`
--
ALTER TABLE `cubes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cube_attributes`
--
ALTER TABLE `cube_attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cube_commands`
--
ALTER TABLE `cube_commands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cube_game_moves`
--
ALTER TABLE `cube_game_moves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cube_monsters`
--
ALTER TABLE `cube_monsters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cube_worlds`
--
ALTER TABLE `cube_worlds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `error_codes`
--
ALTER TABLE `error_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_move_messages`
--
ALTER TABLE `game_move_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monsters`
--
ALTER TABLE `monsters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `commands`
--
ALTER TABLE `commands`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `cubes`
--
ALTER TABLE `cubes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `cube_attributes`
--
ALTER TABLE `cube_attributes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `cube_commands`
--
ALTER TABLE `cube_commands`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cube_game_moves`
--
ALTER TABLE `cube_game_moves`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `cube_monsters`
--
ALTER TABLE `cube_monsters`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `cube_worlds`
--
ALTER TABLE `cube_worlds`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `error_codes`
--
ALTER TABLE `error_codes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `game_move_messages`
--
ALTER TABLE `game_move_messages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `monsters`
--
ALTER TABLE `monsters`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;