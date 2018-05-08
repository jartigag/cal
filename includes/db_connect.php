<?php
// Variables de acceso a la Base de Datos:
$pdo_description='mysql:dbname=db_databasename;host=localhost;port=3306';
$pdo_user="mysql_user";
$pdo_pass="mysql_password";
// Objeto de tipo PDO:
$pdo = new PDO( $pdo_description, $pdo_user, $pdo_pass );
if ($pdo==NULL) {
	die("error al conectarse a la base de datos");
}
?>
