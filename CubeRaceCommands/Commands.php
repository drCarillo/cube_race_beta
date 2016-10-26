<?php
namespace CubeRaceCommands;

/**
* Basic commands to implement.
*
* @author Chris Carillo <drcarillo@gmail.com> 2016-10-25
*/  
interface GameCommands
{
    function move($direction, $game_id, $cube_id, $player_id);
}