<?php
include "include/PDO.php";

if($_POST){
	if (isset($_POST['add'])) {
		$db->save("users", $_POST);
	}elseif(isset($_POST['edit'])){
		$db->update("users", $_POST);
	}
}

if($_GET){
	if (isset($_GET['del'])) {
		$condition = "id=".$_GET['id'];
		$db->delete("users", $condition);
	}
}