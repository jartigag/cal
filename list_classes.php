<?php
include 'includes/db_connect.php';

session_start();
/*TODO: - Tabla para listar las clases disponibles.
		- Link en cada una para apuntarse*/

//function print_tabla() {
	$navbar = file_get_contents("assets/navbar.html");
	$template_table = file_get_contents("assets/classes_table.html");	//$template_table es una tabla en hml
	$row = file_get_contents("assets/classes_row.html");				//$row es una fila en html
	$result = str_replace("##navbar##", $navbar, $template_table);
	$result = str_replace("##username##", $_SESSION['username'], $result);
	$rows = ""; 														//en $rows se concatenará cada fila generada

	$stmt=$pdo->query("SELECT * FROM classes");
	if ($stmt===false) {
		die("error en la query");
	}
	$class=$stmt->fetch(PDO::FETCH_ASSOC);
	while($class) { //TODO: falla aquí
		$result_row = str_replace("#id#", $class['id'], $row);
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
//}
//print_tabla();

?>