<?php
include 'db_connect.php';
include 'functions_login.php';

session_start();

if(isset($_POST['user'], $_POST['hashed_password'])) { 
	$user = $_POST['user'];
	$hashed_password = $_POST['hashed_password'];
	if(login($user,$hashed_password,$pdo) == true) {
		// Login correcto
		header("Location: /cal/cal.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
	} else {
		// Login incorrecto
		header("Location: /cal/login.php?error=true"); // IMPORTANTE: se ha usado /cal/ como parte de la url
	}
} else { 
	// No se han enviado las variables POST correctas
	echo 'petición inválida';
}
?>