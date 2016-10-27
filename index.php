<?php
error_reporting(E_ALL);	
ini_set('display_errors', '1');

require_once('./Storage/DbStorage.php');

/**
* Reset CubeGame on opening or refresh browser.
* Not in a framework so there's no import view command
* or other for this controller. View (HTML) below.
*
* @author Chris Carillo <drcarillo@gmail.com> 2016-10-22
*/
$storage = new DbStorage();
$storage->updateGameStatus(1, null, 1);  // restart game status: end: 1 for active
$storage->updatePlayerMove(1, 11, 'start', 1, 1); // set player in start cube 11
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cube Race Game Beta For PhoenixOne By CCarillo</title>
     <script src="./js/jquery-1.9.1.js"></script>
     <script src="./js/send_command.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/send_command.css">
</head>

<body>
    <p>Move commands: north, south, east, west, up, down</p>
      <p>Message commands:<br />say (dialog)<br />tell (screen_name) (dialog)<br />yell (dialog)<br />Don't put braces.</p>
        <p>Note: refreshing browser restarts game.</p>
          <br />
    <form name='player_console' id='player_console' action='#' method='post'>
          <p>Type Command Here:</p>
            <input type='text' name='command' id='command'><br />
            <input type='hidden' name='cube_id' id='cube_id' value='11'>
            <p><input type='submit' name='submit_command' id='submit_command' value='Send Command'></p>
    </form>
      <div name='message_header' id='message_header'>
        <p>Game Message is displayed here:</p>
      </div>
    <div name='message' id='message'>
      
    </div>
</body>

</html>