<?php
include 'includes/db_connect.php';

// Objeto de tipo PDO:
$pdo = new PDO( $pdo_description, $pdo_user, $pdo_pass );
if ($pdo==NULL) {
	die("error al conectarse a la base de datos");
}

$consulta = "SELECT course, count(*) FROM events WHERE teacher=0 group by course";
$resultado = $pdo->query($consulta);
if (!$resultado) {
	die('No se pudo consultar:' . mysql_error());
}

$k=0;
while(($row=$resultado->fetch(PDO::FETCH_NUM))!=null){
	foreach ($row as $key => $value) {
		$result[$k][$key] = $value;
	}
	$k++;
}

header('Content-type: application/json; charset=utf-8');
print json_encode($result);
?>