<?php
include 'db_connect.php';
include 'functions.php';

session_start();

if(isset($_POST['course'], $_POST['lesson'], $_POST['price'], $_POST['datetimeStart'], $_POST['datetimeEnd'])) {
	// Sanear y validar los datos recibidos
	$course = filter_input(INPUT_POST, 'course', FILTER_SANITIZE_STRING);
	$lesson = filter_input(INPUT_POST, 'lesson', FILTER_SANITIZE_STRING);
	$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FLOAT);
	//$datetimeStart = filter_input(INPUT_POST, 'datetimeStart', FILTER_SANITIZE_STRING); //TODO: se puede sanear una fecha?
	//$datetimeEnd = filter_input(INPUT_POST, 'datetimeEnd', FILTER_SANITIZE_STRING); //TODO: se puede sanear una fecha?
	$datetimeStart=$_POST['datetimeStart'];
	$datetimeEnd=$_POST['datetimeEnd'];
	    create_class($course,$lesson,$price,$datetimeStart,$datetimeEnd,$pdo);
	    echo 'clase creada!';
	    exit();
} else { 
	// No se han enviado las variables POST correctas
	echo 'petición inválida';
}
?>