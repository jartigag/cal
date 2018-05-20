<?php
include 'includes/db_connect.php';
//FIXME: NO SE MUESTRA MÁS DE UN ALUMNO EN LA TABLA

session_start();

function print_tabla($classId,$result,$teacher,$pdo) {

	$row = file_get_contents("assets/students_row.html");	//$row es una fila en html
	$rows = ""; 											//en $rows se concatenará cada fila generada

	$result = str_replace("##username##", $_SESSION['username'], $result);

	// reemplazar #lesson# y #course#
	$stmt=$pdo->query("SELECT lesson,course FROM classes WHERE id=".$classId);
	if ($stmt===false) {
		die("petición inválida");
	}
	while($class=$stmt->fetch(PDO::FETCH_ASSOC)) {
		$result = str_replace("#lesson#", $class['lesson'], $result);
		$result = str_replace("#course#", $class['course'], $result);
	}

	if ($teacher) { //si el usuario es el profesor de esta clase:
		// reemplazar #user_id# y #date_joined#
		$stmt=$pdo->query("SELECT date_time,user_id FROM events WHERE teacher=0 AND class_id=".$classId);
		if ($stmt===false) {
			die("petición inválida");
		}
		while($student=$stmt->fetch(PDO::FETCH_ASSOC)) {
			$result_row = str_replace("#user_id#", $student['user_id'], $row);
			$result_row = str_replace("#date_joined#", $student['date_time'], $result_row);

			// reemplazar #username#
			$stmt=$pdo->query("SELECT username FROM users WHERE id=".$student['user_id']);
			if ($stmt===false) {
				die("petición inválida");
			}
			while($user=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$result_row = str_replace("#username#", $user['username'], $result_row);
			}

			$rows = $rows.$result_row;
		}
	} else { //si el usuario no es el profesor de esta clase:
		$rows = '<tr><td colspan=3>Sólo el profesor de esta clase tiene permiso para acceder a esta información</td></tr>';
	}

	$result = str_replace('##filas##', $rows, $result);
	$result = str_replace("#class_id#", $classId, $result);
	print($result);
}

if (isset($_SESSION['user_id'])&&isset($_GET['class_id'])) {
	$userId=$_SESSION['user_id'];
	$classId=$_GET['class_id'];
	try  {
		$stmt = $pdo->prepare("SELECT teacher FROM events WHERE user_id=".$userId." AND class_id=".$classId." limit 1");
	    $stmt->execute();
	    $teacher=0;
		while($res=$stmt->fetch(PDO::FETCH_ASSOC)) { $teacher = $res['teacher'];} // para saber si el usuario es profesor de esta clase
		$navbar = file_get_contents("assets/navbar.html");
		$navbar = str_replace( // Poner el nav-link de la página actual activo
			'<li class="nav-item"><a class="nav-link" href="list_classes.php">Ver clases</a></li>',
		    '<li class="nav-item active"><a class="nav-link" href="list_classes.php">Ver clases</a></li>', $navbar);
		$template_table = file_get_contents("assets/students_table.html");	//$template_table es una tabla en hml
		$result = str_replace("##navbar##", $navbar, $template_table);

		print_tabla($classId,$result,$teacher,$pdo);

	} catch (Exception $e) {
		throw $e;
	}
} else {
	header("Location: /cal/login.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>
