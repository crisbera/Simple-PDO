<?php
/**
* Un  ejemplo de clase
*
* Colocaremos la clase vacia para el ejemplo
*
* @package    MiProyecto
* @subpackage Comun
* @author     Pedro Picapiedra < pedropicapiedra@yabadabado.com>
*/
class MySQL {
	private $host;
	private $user;
	private $password;
	private $database;
	private $db_connection;
	private $result;
	
	public function __construct($host, $user, $password, $database){
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
		$this->connection();
		
	}
	
	public function connection(){
		//Connection to MySQL
		$this->db_connection = @mysql_connect($this->host, $this->user, $this->password);
		
		//Select a database
		$db_select = @mysql_select_db($this->database);
		
		return $this->db_connection;
	}
	
	public function find($table = null, $query = null, $options = array()){
		$fields = '*';
		$parameters = '';
		
		if (!empty($options['conditions'])){
			$parameters = ' WHERE '.$options['conditions'];
		}
		if (!empty($options['fields'])){
			$fields = $options['fields'];
		}
		if (!empty($options['order'])){
			$parameters .= ' ORDER BY '.$options['order'];
		}
		
		if (!empty($options['group'])){
			$parameters .= ' GROUP BY '.$options['group'];
		}
		
		if (!empty($options['limit'])){
			$parameters .= ' LIMIT '.$options['limit'];
		}
		
		switch($query){
			case 'all': {
				$sql = "SELECT $fields FROM ".$table. $parameters;
				$query_result = @mysql_query($sql, $this->db_connection);
				while($result_array = @mysql_fetch_array($query_result)) {
					$this->result[] = $result_array;
				}
				break;
			}			
			case 'count': {
				$sql = "SELECT COUNT(*) FROM ".$table. $parameters;
				$query_result = @mysql_query($sql, $this->db_connection);
				if($query_result){
					$result = mysql_fetch_row($query_result);
					$this->result = $result[0];
				}	
				break;
			}

			case 'first': {
				$sql = "SELECT $fields FROM ".$table. $parameters;
				$query_result = @mysql_query($sql, $this->db_connection);
				while($result_array = @mysql_fetch_array($query_result)) {
					$this->result = $result_array;
				}	
				break;
			}
			
			default: {
				$sql = "SELECT $fields FROM ".$table. $parameters;
				$query_result = @mysql_query($sql, $this->db_connection);
				while($result_array = @mysql_fetch_array($query_result)) {
					$this->result[] = $result_array;
				}
			}
		}

		return $this->result;
	}
	
	public function save($data = array()){
	
	}
		
	public function __destruct(){
		//Close the connection
		@mysql_close($this->db_connection);
		
		//Free result
		@mysql_free_result($this->result);
	}	
}

$db = new MySQL($data_connection['host'], $data_connection['user'], $data_connection['password'], $data_connection['database']);
?>