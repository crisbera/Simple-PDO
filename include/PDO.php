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

include ('../config/database.php');

class MyPDO extends PDO{
	private $driver;
	private $port;
	private $host;
	private $user;
	private $password;
	private $database;
	private $db_connection;
	private $result;
	
	public function __construct($driver, $port, $host, $user, $password, $database){
		$this->driver = $driver;
		$this->port = $port;
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;

		$this->connection();
		
	}
	
	public function connection(){
		//Connection to Driver
		try{
		    $this->db_connection = new PDO($this->driver.':host='.$this->host.';dbname='.$this->database, $this->user, $this->password);
		    $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
	    	echo "ERROR: " . $e->getMessage();
		}

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
      			$query = $this->db_connection->query($sql);
				$this->result = $query->fetchAll(PDO::FETCH_ASSOC);
				break;
			}			
			case 'count': {
				$sql = "SELECT * FROM ".$table. $parameters;
				$query = $this->db_connection->query($sql);
				$this->result = $query->rowCount();
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
		$this->db_connection = null;
	}	
}

$db = new MyPDO($data_connection['driver'], $data_connection['port'], $data_connection['host'], $data_connection['user'], $data_connection['password'], $data_connection['database']);


$users = $db->find('usuarios', 'all', array('limit'=>'0, 5'));

foreach ($users as $user):
	echo $user["id"].'<br />';
	echo $user["password"].'<br />';
	echo $user["username"].'<br />';
	echo $user["name"].'<br />';
	echo "__________________________<br />";
endforeach;


$count_users = $db->find('usuarios', 'count', array('limit'=>'0, 5'));

echo $count_users;

?>