<?php
include 'db_connect.php';
include 'functions.php';

session_start();

/*TODO: variables GET para:
https://webalumnos.tlm.unavarra.es:10169/vobj/crea.php?
nombre=diploma&
desc=3&
icon=&
propietario=1&
generador=1&
coin=20-89fe3bbb40f85919203d*/
if (isset($_GET['user_id'])) {
	$desc = $_GET['user_id']; //TODO: debería sanear esta variable GET?
	$propietario = $_SESSION['user_id'];
	//$dateTime = date('Y-m-d H:i:s');
    if(create_event($dateTime,$classId, $userId,$teacher,$pdo)){
    	echo 'diploma entregado!';
    }
} else { 
	// No se ha enviado la variable GET correcta
	echo "petición inválida. falta la variable GET 'user_id'";
}
?>