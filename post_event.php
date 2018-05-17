<?php
include 'includes/db_connect.php';
//TODO: recibe POST vars
//TODO: query UPDATE
$stmt=$pdo->query("SELECT * FROM classes");
if ($stmt===false) {
	die("error en la query");
}
?>