<?php
//TODO: USAR PARA LOS NÚMEROS: $i_userid = intval($i_userid);
include 'includes/db_connect.php';
session_start();
if (isset($_SESSION['user_id'])) { //TODO: controlar que sea el profesor
	if (isset($_GET['class_id'])) { //TODO: devolver error si la clase no existe
		$classId = $_GET['class_id'];
		//function print_tabla() {
			$navbar = file_get_contents("assets/navbar.html");
			$template_table = file_get_contents("assets/students_table.html");	//$template_table es una tabla en hml
			$row = file_get_contents("assets/students_row.html");				//$row es una fila en html
			$rows = ""; 														//en $rows se concatenará cada fila generada
			$result = str_replace("##navbar##", $navbar, $template_table);
			//TODO: obtener ##lesson##, ##course##
			if (!isset($_SESSION['username'])) {
				$result = str_replace("##username##", '¡No te has identificado!', $result);
				$result = str_replace('<a class="dropdown-item" href="logout.php">Salir</a>', '<a class="dropdown-item" href="login.html">Indentificarse</a><a class="dropdown-item" href="signup.html">Registrarse</a>', $result);
			} else {
				$result = str_replace("##username##", $_SESSION['username'], $result);
			}

			// reemplazar #user_id# y #date_joined#
			$stmt=$pdo->query("SELECT date_time,user_id FROM events WHERE teacher=0 AND class_id=".$classId);
			if ($stmt===false) {
				die("error en la query");
			}
			while($student=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$result_row = str_replace("#user_id#", $student['user_id'], $row);
				$result_row = str_replace("#date_joined#", $student['date_time'], $result_row);

				// reemplazar #username#
				$stmt=$pdo->query("SELECT username FROM users WHERE id=".$student['user_id']);
				if ($stmt===false) {
					die("error en la query");
				}
				while($user=$stmt->fetch(PDO::FETCH_ASSOC)) {
					$result_row = str_replace("#username#", $user['username'], $result_row);
				}

				$rows = $rows.$result_row;
			}

			// reemplazar #lesson# y #course#
			$stmt=$pdo->query("SELECT lesson,course FROM classes WHERE id=".$classId);
			if ($stmt===false) {
				die("error en la query");
			}
			while($class=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$result = str_replace("#lesson#", $class['lesson'], $result);
				$result = str_replace("#course#", $class['course'], $result);
			}

			$result = str_replace('##filas##', $rows, $result);
			$result = str_replace("#class_id#", $classId, $result);
			print($result);
		//}
		//print_tabla();
	} else { 
		// No se ha enviado la variable GET correcta
		echo "petición inválida. falta la variable GET 'class_id'";
	}
} else {
	header("Location: /cal/login.html"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>