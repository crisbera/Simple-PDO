<?php

include ('include/PDO.php');

/*
* Ejemplo sobre eliminar un registros en la tabla types
*

$tipoId = 22;

$db->connection->beginTransaction();

$numero = $db->find('devices', 'count', array('conditions'=>'type_id='.$tipoId));

if($numero>0){
	$db->connection->rollback();
	echo "Este tipo no puede eliminarse, tiene equipos relacionados";
}else{
	$db->delete('types', 'id='.$tipoId);

	$numero = $db->find('devices', 'count', array('conditions'=>'type_id='.$tipoId));

	if($numero > 0){
		$db->connection->rollback();
	}else{
		$db->connection->commit();
	}
}
*/


/**
* Ejemplo sobre agregar un registros en la tabla types, brands y en devices
*
*
*
*/

$db->connection->beginTransaction();

$types = array(
	"name"=>"Prueba8"
	);

$brands = array(
	"name"=>"Prueba"
	);

if($db->save('types', $types)){
	$type_id = $db->lastInsertId;
	if($db->save('brands', $brands)){
		$brand_id = $db->lastInsertId;
		$devices = array(
			"type_id" => $type_id,
			"brand_id"=> $brand_id,
			"description"=> "Computadora DELL",
			"serie_number"=> "90909",
			"stock_number"=> "22211",
			"model"=> "WEEQ8889",
			"responsible"=> "Juan Perez",
			"purchase_date"=> "2015-02-16",
			"warranty_end"=> "2016-02-16",
			"location"=> "Oficina",
			"status"=> 1
		);

		if($db->save('devices', $devices)){
			$db->connection->commit();
		}else{
			$db->connection->rollback();
		}
	}
}else{
	$db->connection->rollback();
}



/*
SET FOREIGN_KEY_CHECKS = 0; 
TRUNCATE table1; 
SET FOREIGN_KEY_CHECKS = 1;

$count = $dbh->exec("DELETE FROM fruit WHERE colour = 'red'");

SET FOREIGN_KEY_CHECKS = 0; 
TRUNCATE table1; 
SET FOREIGN_KEY_CHECKS = 1;

$users = $db->find('usuarios', 'all', array('limit'=>'0, 5'));

foreach ($users as $user):
	echo $user["id"].'<br />';
	echo $user["password"].'<br />';
	echo $user["username"].'<br />';
	echo $user["name"].'<br />';
	echo "__________________________<br />";
endforeach;

$users_numbers = $db->find('usuarios', 'count');
echo "Numero de usuarios: ".$users_numbers;
echo "<br />";

$first_user = $db->find('usuarios', 'first', array('order'=>'usuarios.id DESC'));
echo "ID: ".$first_user['id']."<br />";
echo "Username: ".$first_user['username']."<br />";
echo "Password: ".$first_user['password'];

$types = $db->find('types', 'all', array('limit'=>'0, 5'));

foreach ($types as $type):
	echo $type["id"].'<br />';
	echo $type["name"].'<br />';
	echo "__________________________<br />";
endforeach;

$count_users = $db->find('usuarios', 'count', array('limit'=>'0, 5'));

echo $count_users;

*/


?>