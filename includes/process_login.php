<?php
include 'db_connect.php';
include 'functions.php';

session_start();

if(isset($_POST['user'], $_POST['hashed_password'])) { 
	$user = $_POST['user'];
	$hashed_password = $_POST['hashed_password'];
	if(login($user,$hashed_password,$pdo) == true) {
		// Login correcto
		header("Location: /cal/mycal.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
	} else {
		// Login incorrecto
		echo 'login inválido';
		//TODO: página de error
	}
} else { 
	// No se han enviado las variables POST correctas
	echo 'petición inválida';
}
?>