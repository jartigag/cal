<?php
include 'db_connect.php';
include 'functions.php';

session_start();

if (isset($_GET['class_id'])) {
	$classId = $_GET['class_id']; //TODO: debería sanear esta variable GET?
	$dateTime = date('Y-m-d H:i:s');
	$userId = $_SESSION['user_id'];
	if ($stmt = $pdo->prepare('SELECT price FROM classes WHERE id= :i')) {
 		$stmt->bindParam(':i', $classId);
 		// Ejecutar la query preparada
 		if ($stmt->execute()) {
 			list($price) = $stmt->fetch(PDO::FETCH_NUM);
 		} else {
 			die('error al obtener el precio de la clase');
 		}
	}
	//TODO: si el usuario es profesor de la clase, devolver error
	if (validate_coin($userId,$price)) {
	    if(join_class($dateTime,$classId,$userId,$price,$pdo)){
	    	echo 'inscrito!';
	    }
	}
} else { 
	// No se ha enviado la variable GET correcta
	echo "petición inválida. falta la variable GET 'class_id'";
}
?>