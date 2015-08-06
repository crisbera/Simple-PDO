<?php
include ('include/PDO.php');

$users = $db->find('users', 'all', array());
?>
<a href="add.php">Agregar usuario</a>
<table border="1">
<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo $user["id"]; ?></td>
		<td><?php echo $user["name"]; ?></td>
		<td><?php echo $user["phone"]; ?></td>
		<td>
			<a href="edit.php?id=<?php echo $user['id']; ?>">Editar</a> | 
			 <a href="controller.php?del&id=<?php echo $user['id']; ?>">Eliminar</a>
			</td>
	</tr>
<?php endforeach; ?>
</table>