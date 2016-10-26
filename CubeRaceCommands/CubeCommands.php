<?php
namespace CubeCommands;

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('Commands.php');

/**
* Call and execute commands from player text input.
* Pass in $storage object to call database or whatever storage
* facility. We could also inject a Storage object in this class.
*
* @author Chris Carillo <drcarillo@gmail.com> 2016-10-25
*/  
class CubeRacePlayerCommands implements \CubeRaceCommands\GameCommands
{
    /**
    * Cube/room players are moving or racing through (dungeon).
    *
    * @var array $cube
    */
    private $cube = null;
    
    /**
    * Cube/room to start game with.
    *
    * @var array $start_cube
    */
    private $start_cube = null;
    
    /**
    * Cube/room attributes (descriptions).
    *
    * @var array $cube_attributes
    */
    private $cube_attributes = null;
    
    /**
    * Cube room monsters (evil or not).
    *
    * @var array $cube_monsters
    */
    private $cube_monsters = null;
    
    /**
    * Current players in the cube/room for say command:
    * players in current game world for yell command:
    * player in the cube for the tell command.
    *
    * @var array $cube_players
    */
    private $cube_players = null;
    
    /**
    * Storage object to retrieve data from whatever system.
    *
    * @var Storage $storage
    */
    private $storage = null;
    
    /**
    * When a player tries a move we return
    * a message that they passed into a cube
    * or that the can't pass that (direction) wall, ceiling, floor.
    *
    * @var array $game_move_messages
    */
    private $game_move_messages = null;
    
    /**
    * Current game status.
    *
    * @var integer $game_status
    */
    public $game_status = 1;
    
    /**
	* Default contructor: could add Storage injection.
	*/
	public function __construct($storage, $start_cube) {
	    $this->storage = $storage;
	    $this->getGameMoveMesssages();
	    $this->start_cube = $this->getCube($start_cube);
	}
	
	public function getCommand() {
	    return 'made it here again...';
	}
	
	/**
	* Player chose to move north.
	* Check if the current cube (room) is solid or transparent:
	* if transparent wall allows passage (adjoining cube/room attached).
	* 
	* If move to new cube then get all players, cube attributes, cube monsters
	* etc. that are in the cube.
	*
	* @param integer $game_id
	* @param integer $cube_id
	* @param integer $player_id
	*
	* @return string $message
	*/
	public function move($direction, $game_id, $cube_id, $player_id) {
	    $message           = null;
	    $this->game_status = $this->storage->getGameStatus($game_id);
	    if ($this->game_status === 0) return $message = $this->gameIsOver();  // game not restarted yet
	    
	    $current_cube           = $this->getCube($cube_id);
	    $current_direction_id   = strtolower($direction) . '_id';      // for north, south, east, west, up, down
	    $current_direction_room = strtolower($direction) . '_room';    // move into a room/cube
	    $current_direction_wall = strtolower($direction) . '_wall';    // you can't move through the wall
	    //var_dump($this->game_status); exit;
	    if (($current_cube && !empty($current_cube[$current_direction_id]) && $current_cube['solid'] != 1) || $current_cube['entrance'] == $direction) {
	        $this->storage->updatePlayerMove($player_id, $current_cube[$current_direction_id], $direction, 1, 1); // you're moving
	        $this->cube            = $this->getCube($current_cube[$current_direction_id]);                        // cube/room id then you can move in
	        $this->cube_players    = $this->getSayPlayers($game_id, $current_cube[$current_direction_id]);        // get all players in the room/cube
	        $this->cube_attributes = $this->getCubeAttributes($current_cube[$current_direction_id], 1);
	        $this->cube_monsters   = $this->getCubeMonsters($current_cube[$current_direction_id], 1);
	        
	        if (empty($this->cube['win_here'])) {
	            $message .= '<br />' . $this->game_move_messages[$current_direction_room];   // there's a room to enter: current room transparent
	            $message .= '<br />' . $this->processCubeAttributes();
	            $message .= $this->getPlayerScreenNames();
	            if ($this->cube_monsters) $message .= '<br />Monster: ' . $this->cube_monsters[0]['screen_name'] . ' says: ' . $this->cube_monsters[0]['default_greeting'];
	        } else {
	            $message .= $this->processCubeAttributes();
	            $message .= '<br />' . $this->gameIsOver();
	            $this->storage->updateGameStatus(1, $player_id, 0);  // game won so change game status: end: 0 for active
	            $this->storage->updatePlayerMove($player_id, $current_cube[$current_direction_id], $direction, 1, 0); // set player as winner
	        }
	    } else {
	        $message = '<br />' . $this->game_move_messages[$current_direction_wall] . '<br />'; // can't move through this wall vs room: not transparent
	    }
	    
	    return $message;
	}
    
    // getters
    
    /**
	* Get the $game_move_messages to return a meesage
	* when a player makes a move command.
	* 
	* @return array $this->game_move_messages on success
	* @return boolean false on error
	*/
	public function getGameMoveMesssages() {
	    return $this->game_move_messages = $this->storage->getGameMoveMessages(1);
	}
	
	/**
	* Get the cube/room and its properties.
	* 
	* @param integer $cube_id
	*
	* @return array $this->cube on success
	* @return boolean false on error
	*/
	public function getCube($cube_id) {
	    $cube = $this->storage->getCube($cube_id);
	    
	    if ($cube) $this->cube = $cube;
	      else $this->cube = false;
	    
	    return $this->cube;
	}
	
	/**
	* Get the cube attributes if any.
	* 
	* @param integer $cube_id
	* @param integer $active
	*
	* @return array $this->cube_attributes on success
	* @return boolean false on error
	*/
	public function getCubeAttributes($cube_id, $active) {
	    $attribute_ids = $this->storage->getCubeAttributeIds($cube_id, $active);
	    
	    if ($attribute_ids) {
	        foreach ($attribute_ids as $id) {
	            $this->cube_attributes[] = $this->storage->getAttribute($id, 1);
	        }
	    } else {
	        $this->cube_attributes = false;
	    }
	    
	    return $this->cube_attributes;
	}
	
	/**
	* Get the monsters in the cube if any.
	* 
	* @param integer $cube_id
	* @param integer $active
	*
	* @return array $this->cube_monsters on success
	* @return boolean false on error
	*/
	public function getCubeMonsters($cube_id, $active) {
	    $monster_ids = $this->storage->getCubeMonsterIds($cube_id, $active);
	    
	    if ($monster_ids) {
	        foreach ($monster_ids as $id) {
	            $this->cube_monsters[] = $this->storage->getMonster($id, $active);
	        }
	    } else {
	        $this->cube_monsters = false;
	    }
	    
	    return $this->cube_monsters;
	}
	
	/**
	* Get the other cube players in the room if any.
	* 
	* @param integer $game_id
	* @param integer $cube_id
	*
	* @return array $this->cube_players on success
	* @return boolean false on error
	*/
	public function getSayPlayers($game_id, $cube_id) {
	    $player_ids = $this->storage->getSayPlayerIds($game_id, $cube_id);
	    
	    if ($player_ids) {
	        foreach ($player_ids as $id) {
	            $this->cube_players[] = $this->storage->getPlayer($id);
	        }
	    } else {
	        $this->cube_players = false;
	    }
	    
	    return $this->cube_players;
	}
	
	/**
	* Get the all cube players in the current game (world).
	* 
	* @param integer $game_id
	*
	* @return array $this->cube_players on success
	* @return boolean false on error
	*/
	public function getYellPlayers($game_id) {
	    $player_ids = $this->storage->getYellPlayerIds($game_id);
	    
	    if ($player_ids) {
	        foreach ($player_ids as $id) {
	            $this->cube_players[] = $this->storage->getPlayer($id);
	        }
	    } else {
	        $this->cube_players = false;
	    }
	    
	    return $this->cube_players;
	}
	
	/**
	* Get the all cube players in the current game (world).
	* Probably don't need this method: may remove: TODO
	* 
	* @param integer $game_id
	*
	* @return array $this->cube_players on success
	* @return boolean false on error
	*/
	public function getTellPlayer($player_id) {
	    $player = $this->storage->getPlayer($player_id);
	    
	    if ($player) $this->cube_players[] = $player;
	      else $this->cube_players = false;
	    
	    return $this->cube_players;
	}
	
	// utilities
	
	/**
	* Return that the game is already over: stops unnecessary processing
	*
	* @return string $message
	*/
	private function gameIsOver()
	{
	    $message = null;
	    return $message = '<p>This game is over. Cheers!</p>Refresh browser to start new game.';
	}
	
	/**
	* Process the the screen names in the room/cube.
	*
	* @return string $message
	*/
	private function getPlayerScreenNames()
	{
	    $message = null;
	    $message .= '<br />Occupants:';
	    foreach ($this->cube_players as $key => $name) {
	        $message .= '<br />Player: ' . $this->cube_players[$key]['screen_name'];
	        if ($this->cube_players[$key]['screen_name'] == 'test_player') $message .= ' - you';
	    }
	    
	    return $message;
	}
	
	/**
	* Process the the attributes (descriptions) for the room/cube.
	*
	* @return string $message
	*/
	private function processCubeAttributes()
	{
	    $message = null;
	    foreach ($this->cube_attributes as $key => $value) {
	        $message .= '<p>' . $this->cube_attributes[$key]['description'] . '<p>';
	    }
	    return $message;
	}
	
	public function __destruct() {}
}