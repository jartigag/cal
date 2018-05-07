<?php
include 'db_connect.php';
include 'functions.php';

session_start();

if(isset($_POST['course'], $_POST['lesson'], $_POST['price'], $_POST['datetimeStart'], $_POST['datetimeEnd'])) {
	// Sanear y validar los datos recibidos
	//TODO: sanear cada uno con STRING,FLOAT,DATE? mediante filter_input(INPUT_POST, 'course', FILTER_SANITIZE_STRING);
	$userId = $_SESSION['user_id'];
	$course = $_POST['course'];
	$lesson = $_POST['lesson'];
	$price = $_POST['price'];
	$gen_price = 10; //TODO: Consultar Precio de la API de vobj para crear un objeto generador (en este caso, el diploma de la clase)
	$datetimeStart = $_POST['datetimeStart'];
	$datetimeEnd = $_POST['datetimeEnd'];
	//if (validate_coin($userId,$gen_price)) {
		if (create_class($course,$lesson,$price,$datetimeStart,$datetimeEnd,$pdo)){
			echo 'clase creada!';
			exit();
		}	
	//}
} else { 
	// No se han enviado las variables POST correctas
	echo 'petición inválida';
}
?>
