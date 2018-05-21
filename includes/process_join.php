<?php
include 'db_connect.php';
include 'functions_create.php';
include 'functions_tlmcoin.php';
include 'functions_vobj.php';

session_start();
if (isset($_SESSION['user_id'])) {
	if (isset($_GET['class_id'])) {
		$classId = $_GET['class_id'];
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
		//WIP: devolver errores
		if (validate_coin($userId,$price,$pdo)) {
			$res = join_class($dateTime,$classId,$userId,$price,$pdo);
		    if ($res===true) {
		    	header("Location: /cal/cal.php?joined_class"); // IMPORTANTE: se ha usado /cal/ como parte de la url
		    } else {
		    	header("Location: /cal/list_classes.php?error=".$res); // IMPORTANTE: se ha usado /cal/ como parte de la url
		    }
		}
	} else { 
		// No se ha enviado la variable GET correcta
		echo "petición inválida. falta la variable GET 'class_id'";
	}
} else {
	header("Location: /cal/login.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>