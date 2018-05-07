<?php
//TODO: USAR PARA LOS NÚMEROS: $i_userid = intval($i_userid);
include 'includes/db_connect.php';
session_start();
//TODO: controlar si está logeado
if (isset($_GET['class_id'])) {
	$classId = $_GET['class_id'];
	//function print_tabla() {
		$navbar = file_get_contents("assets/navbar.html");
		$template_table = file_get_contents("assets/students_table.html");	//$template_table es una tabla en hml
		$row = file_get_contents("assets/students_row.html");				//$row es una fila en html
		$result = str_replace("##navbar##", $navbar, $template_table);
		$result = str_replace("##class_id##", $classId, $result);
		$rows = ""; 														//en $rows se concatenará cada fila generada
		//TODO: obtener ##lesson##, ##course##
		if (!isset($_SESSION['username'])) {
			$result = str_replace("##username##", '¡No te has identificado! <a class="p-2 text-white" href="login.html">Identifícate</a> <a class="p-2 text-white" href="signup.html">o Regístrate</a>', $result);
		} else {
			$result = str_replace("##username##", '<a class="p-2 text-white" href="#">'.$_SESSION['username'].'</a><a class="p-2 text-white" href="logout.php">(Salir)</a>', $result);
		}

		$stmt=$pdo->query("SELECT date_time,user_id FROM events WHERE teacher=0 AND class_id=".$classId);
		if ($stmt===false) {
			die("error en la query");
		}
		$student=$stmt->fetch(PDO::FETCH_ASSOC);
		while($student) { //TODO: falla aquí
			$result_row = str_replace("#user_id#", $student['user_id'], $row);
			$result_row = str_replace("#date_joined#", $student['date_time'], $result_row);
			//TODO: obtener ##username##

			$rows = $rows.$result_row;

			$student=$stmt->fetch(PDO::FETCH_ASSOC);
		}
		$result = str_replace('##filas##', $rows, $result);
		print($result);
	//}
	//print_tabla();
} else { 
	// No se ha enviado la variable GET correcta
	echo "petición inválida. falta la variable GET 'class_id'";
}
?>