<?php
include 'db_connect.php';
include 'functions.php';

session_start();

if(isset($_POST['course'], $_POST['lesson'], $_POST['price'], $_POST['datetimeStart'], $_POST['datetimeEnd'])) {
	// Sanear y validar los datos recibidos
	//TODO: sanear cada uno con STRING,FLOAT,DATE? mediante filter_input(INPUT_POST, 'course', FILTER_SANITIZE_STRING);
	$course = $_POST['course'];
	$lesson = $_POST['lesson'];
	$price = $_POST['price'];
	$datetimeStart = $_POST['datetimeStart'];
	$datetimeEnd = $_POST['datetimeEnd'];
    if(create_class($course,$lesson,$price,$datetimeStart,$datetimeEnd,$pdo)){
    	echo 'clase creada!';
    	exit();
    }
} else { 
	// No se han enviado las variables POST correctas
	echo 'petición inválida';
}
?>
