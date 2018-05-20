<?php
include 'includes/db_connect.php';
//TODO: controlar si es el profesor (entonces link en sus clases que llevan a list_students.php?class_id=X)
//		o alumno (entonces sin link en clases)

session_start();

function print_tabla($result,$pdo) {
	$row = file_get_contents("assets/classes_row.html");	//$row es una fila en html
	$rows = ""; 											//en $rows se concatenará cada fila generada

	if (!isset($_SESSION['username'])) {
		$result = str_replace("##username##", '¡No te has identificado!', $result);
		$result = str_replace('<a class="dropdown-item" href="logout.php">Salir</a>', '<a class="dropdown-item" href="login.php">Indentificarse</a><a class="dropdown-item" href="signup.html">Registrarse</a>', $result);
	} else {
		$result = str_replace("##username##", $_SESSION['username'], $result);
	}

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

		$rows = $rows.$result_row;

		$class=$stmt->fetch(PDO::FETCH_ASSOC);
	}
	$result = str_replace('##filas##', $rows, $result);
	print($result);
}

$result="";

$navbar = file_get_contents("assets/navbar.html");
$navbar = str_replace( // Poner el nav-link de la página actual activo
	'<li class="nav-item"><a class="nav-link" href="list_classes.php">Ver clases</a></li>',
    '<li class="nav-item active"><a class="nav-link" href="list_classes.php">Ver clases</a></li>', $navbar);

$template_table = file_get_contents("assets/classes_table.html");	//$template_table es una tabla en hml
$result = str_replace("##navbar##", $navbar, $template_table);

print_tabla($result,$pdo);

?>