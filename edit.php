<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Editar usuario</title>
</head>
<body>
	<?php
		include "include/PDO.php";
		$conditions = array(
			'conditions'=>'id='.$_GET['id']
		);
		$user = $db->find('users', 'first', $conditions);
	?>
	<form action="controller.php" method="POST">
		<input type="hidden" name="id" value="<?php echo $user['id']; ?>">
		<input type="hidden" name="edit">
		<p>Nombre: <input type="text" name="name" value="<?php echo $user['name']; ?>"></p>
		<p>Teléfono: <input type="text" name="phone" value="<?php echo $user['phone']; ?>"></p>
		<p>	<input type="submit" value="Guardar"></p>
	</form>
</body>
</html>