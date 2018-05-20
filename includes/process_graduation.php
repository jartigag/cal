<?php
include 'db_connect.php';
include 'functions_create.php';
include 'functions_tlmcoin.php';
include 'functions_vobj.php';

session_start();

if (isset($_SESSION['user_id'])) { //TODO: controlar que sea el profesor
	if (isset($_POST['user_id'],$_POST['class_id'])) {
		$classId = $_POST['class_id'];
		$propietario_dest = $_POST['user_id']; //TODO: debería sanear esta variable GET?
		$dateTime = date('Y-m-d H:i:s');
	    if(transfer_diploma($dateTime,$classId,$propietario_dest,$pdo)){
	    	echo '   - diploma entregado!';
	    }
	} else { 
		// No se ha enviado la variable GET correcta
		echo "petición inválida";
	}
} else {
	header("Location: /cal/login.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>