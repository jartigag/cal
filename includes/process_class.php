<?php
include 'db_connect.php';
include 'functions_create.php';
include 'functions_tlmcoin.php';
include 'functions_vobj.php';

session_start();

if(isset($_POST['course'], $_POST['lesson'], $_POST['price'], $_POST['datetimeStart'], $_POST['datetimeEnd'])) {
	// Sanear y validar los datos recibidos
	//TODO: sanear cada uno con STRING,FLOAT,DATE? mediante filter_input(INPUT_POST, 'course', FILTER_SANITIZE_STRING);
	$userId = $_SESSION['user_id'];
	$course = $_POST['course'];
	$lesson = $_POST['lesson'];
	$price = $_POST['price'];
	$genPrice = 10; //TODO: Consultar Precio de la API de vobj para crear un objeto generador (en este caso, el diploma de la clase)
	$datetimeStart = $_POST['datetimeStart'];
	$datetimeEnd = $_POST['datetimeEnd'];
	if (validate_coin($userId,$genPrice,$pdo)) {
		if (create_class($course,$lesson,$price,$datetimeStart,$datetimeEnd,$pdo)){
			header("Location: /cal/mycal.php?created_class"); // IMPORTANTE: se ha usado /cal/ como parte de la url
		}	
	}
} else { 
	// No se han enviado las variables POST correctas
	echo 'petición inválida';
}
?>
