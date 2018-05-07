<?php
include 'db_connect.php';
include 'functions.php';

session_start();

if (isset($_GET['class_id'])) {
	$dateTime = date('Y-m-d H:i:s');
	$userId = $_SESSION['user_id'];
	$teacher = 0;
	$classId = $_GET['class_id']; //TODO: debería sanear esta variable GET?
	//TODO: si el usuario es profesor de la clase, devolver error 
    if(create_event($dateTime,$classId, $userId,$teacher,$pdo)){
    	echo 'inscrito!';
    }
} else { 
	// No se ha enviado la variable GET correcta
	echo "petición inválida. falta la variable GET 'class_id'";
}
?>