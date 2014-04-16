<?php

include ('config/database.php');
include ('include/mysql_class.php');

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

?>