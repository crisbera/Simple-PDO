<?php
/**
 * Archivo de clase de conexion PDO
 *
 * Clase que permite acciones CRUD usando PDO
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @package    PDO
 * @author     Cristian Bernal <crisbera@gmail.com>
 */

class ClassPDO{
	public  $connection;
	private $dsn;
	private $drive;
	private $host;
	private $database;
	private $username;
	private $password;
	public  $result;
	public  $lastInsertId;
	public $numberRows;

/**
  * Constructor de la clase
  * @return void
  */
	public function __construct($drive = 'mysql', $host = 'localhost', $database = 'test', $username = 'root', $password = ''){
		$this->drive    = $drive;
		$this->host     = $host;
		$this->database = $database;
		$this->username = $username;
		$this->password = $password;
		$this->connection();
	}

/**
  * Méto de conexión a la base de datos.
  *
  * Método que permite establecer una conexión a la base de datos
  *
  * @return void
  */
	private function connection(){
		$this->dsn = $this->drive.':host='.$this->host.';dbname='.$this->database;
		try{
			$this->connection = new PDO(
				$this->dsn,
				$this->username,
				$this->password
			);
			$this->connection->setAttribute(
				PDO::ATTR_ERRMODE, 
				PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo "ERROR: " . $e->getMessage();
			die();
		}		
	}

/**
  * Método find
  *
  * Método que sirve para hacer consultas a la base de datos
  *
  * @param string $table nombe de la tabla a consultar
  * @param string $query tipo de consulta
  *  - all
  *  - first
  *  - count
  * @param array $options restriciones en la consulta
  *  - fields
  *  - conditions
  *  - group
  *  - order
  *  - limit
  * @return object
  */
	public function find($table = null, $query = null, $options = array()){
		$fields = '*';
		$parameters = '';

		if(!empty($options['fields'])){
			$fields  = $options['fields'];
		}

		if(!empty($options['conditions'])){
			$parameters = ' WHERE '.$options['conditions'];
		}

		if(!empty($options['group'])){
			$parameters  .= ' GROUP BY '.$options['group'];
		}		

		if(!empty($options['order'])){
			$parameters  .= ' ORDER BY '.$options['order'];
		}

		if(!empty($options['limit'])){
			$parameters  .= ' LIMIT '.$options['limit'];
		}

		switch ($query) {
			case 'all':{
				$sql = "SELECT $fields FROM ".$table.' '.$parameters;
				$this->result = $this->connection->query($sql);
				break;
			}
			case 'count':{
				$sql = "SELECT COUNT(*) FROM ".$table.' '.$parameters;
				$result = $this->connection->query($sql);
				$this->result = $result->fetchColumn();
				break;
			}
			case 'first':{
				$sql = "SELECT $fields FROM ".$table.' '.$parameters;
				$result = $this->connection->query($sql);
				$this->result = $result->fetch();
				break;
			}
			
			default:
				$sql = "SELECT $fields FROM ".$table.' '.$parameters;
				$this->result = $this->connection->query($sql);
				break;
		}

		return $this->result;

	}

	/**
	 * Metodo save 
	 * 
	 * Metodo que sirve para guardar registros
	 * 
	 * @param  $table tabla a consultar
	 * @param  $data valores a guardar
	 * @return object
	 * @author Cristian Bernal <crisbera@gmail.com>
	 */

	public function save($table = null, $data = array()){
		$sql = "SELECT * FROM $table";
		$result = $this->connection->query($sql);

		for ($i=0; $i < $result->columnCount(); $i++) { 
			$meta = $result->getColumnMeta($i);
			$fields[$meta['name']]=null;
		}

		$fieldsToSave="id";
		$valueToSave="NULL";

		foreach ($data as $key => $value) {
			if(array_key_exists($key, $fields)){
				$fieldsToSave .= ", ".$key;
				$valueToSave  .= ", "."\"$value\""; 
			}
		}

		$sql = "INSERT INTO $table ($fieldsToSave)VALUES($valueToSave);";

		$this->result = $this->connection->query($sql);

		$this->lastInsertId = $this->connection->lastInsertId();

		return $this->result;
	}

	/**
	 * Metodo update 
	 * 
	 * Metodo que sirve para actualizar registros
	 * 
	 * @param  $table tabla a consultar
	 * @param  $data valores a actualizar
	 * @return object
	 * @author Cristian Bernal <crisbera@gmail.com>
	 */
	public function update($table = null, $data = array()){
		$sql = "SELECT * FROM $table";
		$result = $this->connection->query($sql);

		for ($i=0; $i < $result->columnCount(); $i++) { 
			$meta = $result->getColumnMeta($i);
			$fields[$meta['name']]=null;
		}		

		if(array_key_exists("id", $data)){
			//Update
			$fieldsToSave = "";
			$id = $data["id"];
			unset($data["id"]);
			$i = 0;

			foreach ($data as $key => $value) {
				if(array_key_exists($key, $fields)){
					if($i==0){
						$fieldsToSave .= $key."="."\"$value\", ";
					}elseif($i == count($data)-1){
						$fieldsToSave .= $key."="."\"$value\"";
					}else{
						$fieldsToSave .= $key."="."\"$value\", ";
					}
				}
				$i++;
			}

			$sql = "UPDATE $table SET $fieldsToSave WHERE $table.id =$id;";
		}
		$this->result = $this->connection->query($sql);

		return $this->result;		
	}

	/**
	 * Metodo delete 
	 * 
	 * Metodo que sirve para eliminar registros
	 * 
	 * @param  $table tabla a consultar
	 * @param  $condition condición a cumplir
	 * @return object
	 * @author Cristian Bernal <crisbera@gmail.com>
	 */
	public function delete($table = null, $condition = null){
		$sql = "DELETE FROM $table WHERE $condition".";";

		$this->result = $this->connection->query($sql);

		$this->numberRows = $this->result->rowCount();

		return $this->result;
	}
}

$db = new ClassPDO();

?>