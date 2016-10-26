<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
* PDO database credentials for local dev environment: no config file for this exercise
*/


//const DBHOST = '127.0.0.1';
const DBUSER = 'root';
const DBPASS = 'root';
const DB     = 'cube_game';

/**
* This class creates a simple usable database handle
* for the purpose of this exercise.
*									 
* @author Chris Carillo <drcarillo@gmail.com> 2016-10-25
*/
class DbStorage
{
    /**
    * PDO database object handle.
    * @var PDO $pdo
    */
    private $pdo = null;

    /**
	* Call the the db handle method the start a db handle to use for this class.
	*/
	public function __construct()
	{
		$this->connectDb();
	}
	
	// CRUD
	
	/**
	* Add a new player move. Staying with one record: update methoth after this.
	* You could record all moves: keep track.
	*
	* @param integer $game_id
	* @param integer $cube_world_id
	* @param integer $cube_id
	* @param integer $player_id
	* @param string  $command
	*
	* @return string $id on success
	* @return boolean false on error
	*/
	public function createPlayerMove($game_id, $cube_world_id, $cube_id, $player_id, $command)
	{
	    try {
	        // prepare
		    $id 	= null;
		    $sql 	= "INSERT INTO cube_game_moves (game_id, cube_world_id, cube_id, player_id, command, active) VALUES (?, ?, ?, ?, ?, ?)";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($game_id, $cube_world_id, $cube_id, $player_id, $command, '1'));
		    
		    if ($query) return $id = $this->pdo->lastInsertId();
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '123', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Update player move.
	* Keeping only one move per player per game at this time.
	* So only updating following column values:
	*
	* @param integer $player_id
	* @param integer $cube_id
	* @param string  $command
	* @param integer $winner
	* @param integer $active
	*
	* @return boolean on success
	* @return boolean false on error
	*/
	public function updatePlayerMove($player_id, $cube_id, $command, $winner, $active)
	{
	    try {
	        $sql 	= "UPDATE cube_game_moves SET cube_id = ?, command = ?, winner = ?, active = ?, updated = NOW() WHERE player_id = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($cube_id, $command, $winner, $active, $player_id));
		    return ($query) ? true : false;
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage;
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '125', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	}
	
	/**
	* Add a new player to the game db.
	*
	* @param string $screen_name
	*
	* @return string $id on success
	* @return boolean false on error
	*/
	public function createPlayer($screen_name, $player_push_message_ip)
	{
	    try {
	        // prepare
		    $id 	= null;
		    $sql 	= "INSERT INTO players (screen_name, push_message_ip, active) VALUES (?, ?, ?)";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($screen_name, $player_push_message_ip, '1'));
		
		    if ($query) return $id = $this->pdo->lastInsertId();
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '123', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Update player to inactive.
	*
	* @param integer $player_id
	*
	* @return boolean on success
	* @return boolean false on error
	*/
	public function updatePlayerInactive($player_id)
	{
	    try {
	        $sql 	= "UPDATE players SET active = 0 WHERE id = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($player_id));
		    return ($query) ? true : false;
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '125', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	}
	
	/**
	* Update game to active or not: win game or restart game.
	* Just using this method to start and end
	* game in UI. Otherwise we'd need to create
	* more games etc. But we're sticking to one
	* 'active' player in one active game for
	* this exercise.
	*
	* @param integer $game_id
	* @param integer $active
	*
	* @return boolean on success
	* @return boolean false on error
	*/
	public function updateGameStatus($game_id, $winner_player_id, $active)
	{
	    try {
	        $sql 	= "UPDATE games SET winner_player_id = ?, active = ? WHERE id = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($winner_player_id, $active, $game_id));
		    return ($query) ? true : false;
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '125', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	}
	
	// getters
	
	/**
	* Get player's push_message_ip (websocket or other: ot implemented)
	*
	* @param integer $player_id
	*
	* @return string $player_id on success
	@ return boolean false on error
	*/
	public function getPlayer($player_id)
	{
	    try {
	        $row    = null;
	        $player = array();
		    // prepare
		    $sql 	= "SELECT id, screen_name, push_message_ip FROM players WHERE id = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($player_id));
		    $row    = $query->fetch();
		    
		    if ($query) return $player = $this->processPlayer($row);
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '124', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Get player_id's for a room (cube) for a say command.
	*
	* @param integer $game_id
	* @param integer $cube_id
	*
	* @return array $player_ids on success
	@ return boolean false on error
	*/
	public function getSayPlayerIds($game_id, $cube_id)
	{
	    try {
	        $row        = null;
	        $player_ids = array();
		    // prepare
		    $sql 	= "SELECT player_id FROM cube_game_moves WHERE game_id = ? AND cube_id = ? AND active = 1";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($game_id, $cube_id));
		    $row    = $query->fetchAll();
		
		    if ($query) return $player_ids = $this->processPlayerIds($row);
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '124', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Get player_id's for a world (active in all cubes) for a yell command.
	*
	* @param integer $game_id
	*
	* @return array $player_ids on success
	@ return boolean false on error
	*/
	public function getYellPlayerIds($game_id)
	{
	    try {
	        $row        = null;
	        $player_ids = array();
		    // prepare
		    $sql 	= "SELECT player_id FROM cube_game_moves WHERE game_id = ? AND active = 1";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($game_id));
		    $row    = $query->fetchAll();
		
		    if ($query) return $player_ids = $this->processPlayerIds($row);
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '124', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Get monster_id's for a room (cube).
	* Currently, only one monster in two different cubes.
	*
	* @param integer $cube_id
	* @param integer $active
	*
	* @return array $monster_ids on success
	@ return boolean false on error
	*/
	public function getCubeMonsterIds($cube_id, $active)
	{
	    try {
	        $row         = null;
	        $monster_ids = array();
		    // prepare
		    $sql 	= "SELECT monster_id FROM cube_monsters WHERE cube_id = ? AND active = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($cube_id, $active));
		    $row    = $query->fetchAll();
		
		    if ($query) return $monster_ids = $this->processMonsterIds($row);
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '124', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Get attribute_id's for a room (cube).
	* Currently, only one monster in two different cubes.
	*
	* @param integer $cube_id
	* @param integer $active
	*
	* @return array $attribute_ids on success
	@ return boolean false on error
	*/
	public function getCubeAttributeIds($cube_id, $active)
	{
	    try {
	        $row           = null;
	        $attribute_ids = array();
		    // prepare
		    $sql 	= "SELECT attribute_id FROM cube_attributes WHERE cube_id = ? AND active = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($cube_id, $active));
		    $row    = $query->fetchAll();
		
		    if ($query) return $attribute_ids = $this->processAttributeIds($row);
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '124', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Get monster(s) for any given cube (room).
	* Only one monster in a room currently. (if)
	*
	* @param integer $monster_id
	* @param integer $active
	*
	* @return array $monster on success
	@ return boolean false on error
	*/
	public function getMonster($monster_id, $active)
	{
	    try {
	        $row     = null;
	        $monster = array();
		    // prepare
		    $sql 	= "SELECT screen_name, default_greeting, default_message, default_farewell FROM monsters WHERE id = ? and active = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($monster_id, $active));
		    $row    = $query->fetch();
		    
		    if ($query) return $monster = $this->processMonster($row);
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '124', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Get attribute(s) for any given cube (room).
	* Only one attribute in a room currently. (if)
	*
	* @param integer $attribute_id
	* @param integer $active
	*
	* @return array $attribute on success
	@ return boolean false on error
	*/
	public function getAttribute($attribute_id, $active)
	{
	    try {
	        $row     = null;
	        $attribute = array();
		    // prepare
		    $sql 	= "SELECT description FROM attributes WHERE id = ? and active = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($attribute_id, $active));
		    $row    = $query->fetch();
		    
		    if ($query) return $attribute = $this->processAttribute($row);
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '124', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Get game move messages to help direct player.
	* Using wildcard but am aware using each column
	* creates better performance.
	*
	* @param integer $game_move_messages_id
	*
	* @return array $messages on success
	@ return boolean false on error
	*/
	public function getGameMoveMessages($game_move_messages_id)
	{
	    try {
	        $row     = null;
	        $messages = array();
		    // prepare
		    $sql 	= "SELECT * FROM game_move_messages WHERE id = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($game_move_messages_id));
		    $row    = $query->fetch();
		    
		    if ($query) return $messages = $this->processGameMoveMessages($row);
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '124', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Get cube (room) data.
	* Using north, south, east, west, up, and down ids
	* as attached cubes (if any) bt these could also
	* be used as wall ids to for a more complext world structure
	* and build algorithm.
	*
	* Using wildcard but am aware using each column
	* creates better performance.
	*
	* @param integer $cube_id
	*
	* @return array $cube on success
	@ return boolean false on error
	*/
	public function getCube($cube_id)
	{
	    try {
	        $row  = null;
	        $cube = array();
		    // prepare
		    $sql 	= "SELECT * FROM cubes WHERE id = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($cube_id));
		    $row    = $query->fetch();
		    
		    if ($query) return $cube = $this->processCube($row);
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '124', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	/**
	* Get current game status (only one currently).
	*
	* @param integer $game_id
	*
	* @return integer $active on success
	@ return boolean false on error
	*/
	public function getGameStatus($game_id)
	{
	    try {
	        $row         = null;
	        $game_status = null;
		    // prepare
		    $sql 	= "SELECT active FROM games WHERE id = ?";
		    $query  = $this->pdo->prepare($sql);
		    $query->execute(array($game_id));
		    $row    = $query->fetch();
		    
		    if ($query) return $game_status = $row['active'];
	    } catch (Exception $e) {
	        $pdo_error_code = ($this->pdo) ? $this->pdo->errorCode() : 'no pdo available';
		    $message        = ($this->pdo) ? implode(' ', $this->pdo->errorInfo()) : $e->getMessage();
			// something went wrong : db error
			error_log(json_encode(array('error_code' => '124', 'pdo_code: ' . $pdo_error_code . ' , errmsg: ' . $message)));
			return false;
	    }
	    
	    return false;
	}
	
	// utilities
	
	/**
	* Build an array from db $row to return.
	*
	* @param MySQL resource $row
	*
	* @return array $player on success
	* @return boolean false
	*/
	private function processPlayer($row)
	{
	    $player = array();
	    if (!empty($row['id'])) $player['id'] = $row['id'];
	        else $player['id'] = null;
	    if (!empty($row['screen_name'])) $player['screen_name'] = $row['screen_name'];
	        else $player['screen_name'] = null;
	    if (!empty($row['push_message_ip'])) $player['push_message_ip'] = $row['push_message_ip'];
	        else $player['push_message_ip'] = null;
	    
	    return $player;
	}
	
	/**
	* Build an array from db $row to return.
	*
	* @param MySQL resource $row
	*
	* @return array $players on success
	* @return boolean false
	*/
	private function processPlayerIds($row)
	{
	    $players = array();
	    foreach ($row as $player_id) {
	       $players[] = $player_id['player_id']; 
	    }
	    
	    return $players;
	}
	
	/**
	* Build an array from db $row to return.
	*
	* @param MySQL resource $row
	*
	* @return array $monster on success
	* @return boolean false
	*/
	private function processMonster($row)
	{
	    $monster = array();
	    if (!empty($row['screen_name'])) $monster['screen_name'] = $row['screen_name'];
	        else $monster['screen_name'] = null;
	    if (!empty($row['evi'])) $monster['evil'] = $row['evil'];
	        else $monster['evil'] = null;
	    if (!empty($row['default_greeting'])) $monster['default_greeting'] = $row['default_greeting'];
	        else $monster['default_greeting'] = null;
	    if (!empty($row['default_message'])) $monster['default_message'] = $row['default_message'];
	        else $monster['default_message'] = null;
	    if (!empty($row['default_farewell'])) $monster['default_farewell'] = $row['default_farewell'];
	        else $monster['default_farewell'] = null;
	    
	    return $monster;
	}
	
	/**
	* Build an array from db $row to return.
	*
	* @param MySQL resource $row
	*
	* @return array $attribute on success
	* @return boolean false
	*/
	private function processAttribute($row)
	{
	    $attribute = array();
	    if (!empty($row['description'])) $attribute['description'] = $row['description'];
	        else $attribute['description'] = null;
	    
	    return $attribute;
	}
	
	/**
	* Build an array from db $row to return.
	*
	* @param MySQL resource $row
	*
	* @return array $monster_ids on success
	* @return boolean false
	*/
	private function processMonsterIds($row)
	{
	    $monster_ids = array();
	    foreach ($row as $monster_id) {
	       $monster_ids[] = $monster_id['monster_id']; 
	    }
	    
	    return $monster_ids;
	}
	
	/**
	* Build an array from db $row to return.
	*
	* @param MySQL resource $row
	*
	* @return array $attribute_ids on success
	* @return boolean false
	*/
	private function processAttributeIds($row)
	{
	    $attribute_ids = array();
	    foreach ($row as $attribute_id) {
	       $attribute_ids[] = $attribute_id['attribute_id']; 
	    }
	    
	    return $attribute_ids;
	}
	
	/**
	* Build an array from db $row to return.
	*
	* @param MySQL resource $row
	*
	* @return array $messages on success
	* @return boolean false
	*/
	private function processGameMoveMessages($row)
	{
	    $messages = array();
	    if (!empty($row['north_wall'])) $messages['north_wall'] = $row['north_wall'];
	        else $messages['north_wall'] = null;
	    if (!empty($row['south_wall'])) $messages['south_wall'] = $row['south_wall'];
	        else $messages['south_wall'] = null;
	    if (!empty($row['east_wall'])) $messages['east_wall'] = $row['east_wall'];
	        else $messages['east_wall'] = null;
	    if (!empty($row['west_wall'])) $messages['west_wall'] = $row['west_wall'];
	        else $messages['west_wall'] = null;
	    if (!empty($row['up_wall'])) $messages['up_wall'] = $row['up_wall'];
	        else $messages['up_wall'] = null;
	    if (!empty($row['down_wall'])) $messages['down_wall'] = $row['down_wall'];
	        else $messages['down_wall'] = null;
	    if (!empty($row['north_room'])) $messages['north_room'] = $row['north_room'];
	        else $messages['north_room'] = null;
	    if (!empty($row['south_room'])) $messages['south_room'] = $row['south_room'];
	        else $messages['south_room'] = null;
	    if (!empty($row['east_room'])) $messages['east_room'] = $row['east_room'];
	        else $messages['east_room'] = null;
	    if (!empty($row['west_room'])) $messages['west_room'] = $row['west_room'];
	        else $messages['west_room'] = null;
	    if (!empty($row['up_room'])) $messages['up_room'] = $row['up_room'];
	        else $messages['up_room'] = null;
	    if (!empty($row['down_room'])) $messages['down_room'] = $row['down_room'];
	        else $messages['down_room'] = null;
	    if (!empty($row['active'])) $messages['active'] = $row['active'];
	        else $messages['active'] = null;
	    
	    return $messages;
	}
	
	/**
	* Build an array from db $row to return.
	*
	* @param MySQL resource $row
	*
	* @return array $cube on success
	* @return boolean false
	*/
	private function processCube($row)
	{
	    $cube = array();
	    if (!empty($row['north_id'])) $cube['north_id'] = $row['north_id'];
	        else $cube['north_id'] = null;
	    if (!empty($row['south_id'])) $cube['south_id'] = $row['south_id'];
	        else $cube['south_id'] = null;
	    if (!empty($row['east_id'])) $cube['east_id'] = $row['east_id'];
	        else $cube['east_id'] = null;
	    if (!empty($row['west_id'])) $cube['west_id'] = $row['west_id'];
	        else $cube['west_id'] = null;
	    if (!empty($row['up_id'])) $cube['up_id'] = $row['up_id'];
	        else $cube['up_id'] = null;
	    if (!empty($row['down_id'])) $cube['down_id'] = $row['down_id'];
	        else $cube['down_id'] = null;
	    if (!empty($row['default_level'])) $cube['default_level'] = $row['default_level'];
	        else $cube['default_level'] = null;
	    if (!empty($row['default_attribute'])) $cube['default_attribute'] = $row['default_attribute'];
	        else $cube['default_attribute'] = null;
	    if (!empty($row['entrance'])) $cube['entrance'] = $row['entrance'];
	        else $cube['entrance'] = null;
	    if (!empty($row['screen_name'])) $cube['screen_name'] = $row['screen_name'];
	        else $cube['screen_name'] = null;
	    if (!empty($row['start_game_room'])) $cube['start_game_room'] = $row['start_game_room'];
	        else $cube['start_game_room'] = null;
	    if (!empty($row['solid'])) $cube['solid'] = $row['solid'];
	        else $cube['solid'] = null;
	    if (!empty($row['win_here'])) $cube['win_here'] = $row['win_here'];
	        else $cube['win_here'] = null;
	    if (!empty($row['active'])) $cube['active'] = $row['active'];
	        else $cube['active'] = null;
	    
	    return $cube;
	}
	
	/**
	* Try to create a db connect handle to process data requests on instaniation of an object of this class.
	*
	* @return boolean false on faiure to connect to db
	*/
	private function connectDb()
	{
	    try {
	        $this->pdo = new PDO("mysql:host=localhost;dbname=cube_game", DBUSER, DBPASS, array(PDO::ATTR_EMULATE_PREPARES => false));
	        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    } catch (Exception $e) {
	        error_log(json_encode(array('error_code' => '127', 'error_message: ' => $e->getMessage(), 'FAIL' => true)));
	        return false;
	    }
	}
	
	public function __destruct() {}
}