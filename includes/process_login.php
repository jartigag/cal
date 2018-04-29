<?php
include 'db_connect.php';
include 'functions.php';

session_start();

if(isset($_POST['inputEmail'], $_POST['inputPassword'])) { 
	$email = $_POST['inputEmail'];
	$password = $_POST['inputPassword'];
	if(login($email,$password,$pdo) == true) {
		// Login correcto
		echo 'logeado correctamente';
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