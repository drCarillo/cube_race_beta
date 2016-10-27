<?php
error_reporting(E_ALL);	
ini_set('display_errors', '1');

require_once('../Storage/DbStorage.php');
require_once('../CubeRaceCommands/CubeCommands.php');  // no Composer or autoload for this exercise

use CubeCommands as commands;

/**
* This is the controller for the CubeRace (Dungeon) Game.
*
* @author Chris Carillo <drcarillo@gmail.com> 2016-10-25
*/
try {
    $storage             = new DbStorage();
    $game_commands       = new commands\CubeRacePlayerCommands($storage, 11);
} catch (Exception $e) {
    error_log(json_encode(array('error_code' => '127', 'error_message: ' => $e->getMessage(), 'FAIL' => true)));
	echo json_encode(array('message' => '<br />Something went wrong.<br />Contact administrator.'));
}

$_POST['command'] = 'down';
$_POST['cube_id'] = 1;

// Process POST data and return message
try {
    $command  = $_POST['command'];
    if (empty($command)) throw new Exception('Command was empty.');
    $cube_id  = $_POST['cube_id'];
    if (empty($cube_id)) throw new Exception('cube_id was empty.');
    
    $commands = $storage->getAllCommands();                                              // just for this exercise
    if (empty($command)) throw new Exception('Commands array from database was empty.'); // shouldn't happen
    
    $message        = null;
    $cube_id        = intval($cube_id);
    $command_arr    = explode(' ', $command);
    $command_arr[0] = trim($command_arr[0]);
    $command_arr[0] = strtolower($command_arr[0]);                                       // no other cleaning or validation at this time
                                                                                         // otherwise, need regex or other for XSS attacks
    
    if (!in_array($command_arr[0], $commands)) throw new Exception('Invalid command sent.');
    
    // temporary: need a factory command to build commands for model
    if (in_array($command_arr[0], array('say', 'tell', 'yell'))) {
        // shout outs
        switch ($command_arr[0]) {
            case 'tell':
                $message = $game_commands->tellMessage($command_arr[2], $command_arr[1], $cube_id);
                break;
            case 'say':
                $message = $game_commands->sayMessage($command_arr[2], 'test_player', $cube_id);
                break;
            case 'yell':
                $message = $game_commands->yellMessage($command_arr[2], 'test_player', $cube_id);  // for testing
                break;
            default:
                $message = 'Unble to generate message.';  // shouldn't happen
        }
        echo json_encode(array('message' => $message, 'cube_id' => null));
    } else {
        // moves
        $message = $game_commands->move($command_arr[0], 1, $cube_id, 1);
        echo json_encode(array('message' => $message['message'], 'cube_id' => $message['cube_id']));
    }
} catch (Exception $e) {
    error_log(json_encode(array('error_code' => '127', 'error_message: ' => $e->getMessage(), 'FAIL' => true)));
	echo json_encode(array('message' => '<br />That command does not compute.'));
}


//print_r($game_commands->move('up', 1, 12, 1));
//echo $game_commands->yellMessage('Hi there!', 'tellPlayer', 1);