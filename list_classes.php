<?php
include 'includes/db_connect.php';
//TODO: controlar si está logeado
session_start();
//function print_tabla() {
	$navbar = file_get_contents("assets/navbar.html");
	$navbar = str_replace(
		'<li class="nav-item">
                <a class="nav-link" href="list_classes.php">Ver Clases</a>',
        '<li class="nav-item active">
                <a class="nav-link" href="list_classes.php">Ver Clases</a>', $navbar);
	$template_table = file_get_contents("assets/classes_table.html");	//$template_table es una tabla en hml
	$row = file_get_contents("assets/classes_row.html");				//$row es una fila en html
	$result = str_replace("##navbar##", $navbar, $template_table);
	if (!isset($_SESSION['username'])) {
		$result = str_replace("##username##", '¡No te has identificado! <a class="p-2 text-white" href="login.html">Identifícate</a><a class="p-2 text-white" href="signup.html">o Regístrate</a>', $result);
	} else {
		$result = str_replace("##username##", '<a class="p-2 text-white" href="#">'.$_SESSION['username'].'</a><a class="p-2 text-white" href="logout.php">(Salir)</a>', $result);
	}
	$rows = ""; 														//en $rows se concatenará cada fila generada

	$stmt=$pdo->query("SELECT * FROM classes");
	if ($stmt===false) {
		die("error en la query");
	}
	$class=$stmt->fetch(PDO::FETCH_ASSOC);
	while($class) {
		$result_row = str_replace("#class_id#", $class['id'], $row);
		$result_row = str_replace("#course#", $class['course'], $result_row);
		$result_row = str_replace("#lesson#", $class['lesson'], $result_row);
		$result_row = str_replace("#price#", $class['price'], $result_row);
		$result_row = str_replace("#datetime_start#", $class['datetime_start'], $result_row);
		$result_row = str_replace("#datetime_end#", $class['datetime_end'], $result_row);
		//TODO: profesor

		$rows = $rows.$result_row;

		$class=$stmt->fetch(PDO::FETCH_ASSOC);
	}
	$result = str_replace('##filas##', $rows, $result);
	print($result);
//}
//print_tabla();
?>