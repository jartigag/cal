<?php
include 'db_connect.php';
include 'functions.php';

session_start();

if (isset($_SESSION['user_id'])) { //TODO: controlar que sea el profesor
	if (isset($_GET['user_id'])) {
		$propietario_dest = $_GET['user_id']; //TODO: debería sanear esta variable GET?
		//TODO: $oid = $_GET['oid']; //TODO: debería sanear esta variable GET?
		//TODO: $secret = $_GET['secret']; //TODO: debería sanear esta variable GET?
		$dateTime = date('Y-m-d H:i:s');
	    if(transfer_diploma($dateTime,$propietario_dest,$pdo)){
	    	echo 'diploma entregado!';
	    }
	} else { 
		// No se ha enviado la variable GET correcta
		echo "petición inválida. falta la variable GET 'user_id'";
	}
} else {
	header("Location: /cal/login.html"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>