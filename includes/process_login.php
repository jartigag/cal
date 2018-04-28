<?php
include 'db_connect.php';
include 'functions.php';

if(isset($_POST['inputEmail'], $_POST['inputPassword'])) { 
	$email = $_POST['inputEmail'];
	$password = $_POST['inputPassword'];
	if(login($email, $password, $pdo) == true) {
		// Login correcto
		echo 'logeado correctamente';
		// Sacado de 'https://secure.php.net/manual/en/function.header.php':
		/* Redirect to a different page in the current directory that was requested */
		$host = $_SERVER['HTTP_HOST'];
		header("Location: /cal/mycal.html"); // IMPORTANTE: se ha usado /cal/ como parte de la url
	} else {
		// Login incorrecto
		echo 'login inválido';
	}
} else { 
	// No se han enviado las variables POST correctas
	echo 'petición inválida';
}
?>