<?php
include 'includes/db_connect.php';

session_start();

function print_tabla($userId,$result,$pdo) {

	$row = file_get_contents("assets/diplomas_row.html");	//$row es una fila en html
	$rows = ""; 											//en $rows se concatenará cada fila generada

	$result = str_replace("##username##", $_SESSION['username'], $result);
	$result = str_replace("mycal.php", 'mycal.php?user_id='.$_SESSION['user_id'], $result);

	$stmt=$pdo->query("select class_id,diploma_oid,diploma_secret,date_time from diplomas where entregado=1 and user_id=".$userId);
	if ($stmt===false) {
		die("petición inválida");
	}

	$diploma=$stmt->fetch(PDO::FETCH_ASSOC);
	while($diploma) {
		$result_row = str_replace("#class_id#", $diploma['class_id'], $row);
		$result_row = str_replace("#diploma_oid#", $diploma['diploma_oid'], $result_row);
		$result_row = str_replace("#diploma_secret#", $diploma['diploma_secret'], $result_row);
		$result_row = str_replace("#date_time#", $diploma['date_time'], $result_row);

		$rows = $rows.$result_row;

		$diploma=$stmt->fetch(PDO::FETCH_ASSOC);
	}

	$result = str_replace('##filas##', $rows, $result);
	print($result);

}

if (isset($_SESSION['user_id'])) {
	$userId=$_SESSION['user_id'];
	$template_table = file_get_contents("assets/diplomas_table.html");	//$template_table es una tabla en hml

	if (isset($_GET['user_id'])) {

		if ($userId!==$_GET['user_id']) { //el usuario cuyo calendario quiere verse es distinto del usuario logueado:
			$template_table = str_replace("##filas##",
				"<tr><td colspan=3>Sólo el profesor de esta clase tiene permiso para acceder a esta información</td></tr>",$template_table);
		}

		$navbar = file_get_contents("assets/navbar.html");
		$navbar = str_replace( // Poner el nav-link de la página actual activo
			'<li class="nav-item"><a class="nav-link" href="list_diplomas.php">Diplomas</a>',
		    '<li class="nav-item active"><a class="nav-link" href="list_diplomas.php">Diplomas</a>', $navbar);
		$navbar_res = str_replace('list_diplomas.php',
		    'list_diplomas.php?user_id='.$_SESSION['user_id'], $navbar);
		$result = str_replace("##navbar##", $navbar_res, $template_table);

		print_tabla($userId,$result,$pdo);

	} else {
		echo 'petición inválida. falta variable GET user_id';
	}
} else {
	header("Location: /cal/login.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>
