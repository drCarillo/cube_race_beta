<?php
error_reporting(E_ALL);	
ini_set('display_errors', '1');

require_once('../Storage/DbStorage.php');
require_once('../CubeRaceCommands/CubeCommands.php');

use CubeCommands as commands;

/**
* This is the controller for the CubeRace (Dungeon) Game.
*
* @author Chris Carillo <drcarillo@gmail.com> 2016-10-25
*/
$storage             = new DbStorage();
$game_commands       = new commands\CubeRacePlayerCommands($storage, 11);

print_r($game_commands->move('up', 1, 12, 1));