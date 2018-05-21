<?php
	$template = file_get_contents("assets/cal.html");
	$navbar = file_get_contents("assets/navbar.html");
	$navbar = str_replace( // Poner el nav-link de la página actual activo
	'<li class="nav-item"><a class="nav-link" href="cal.php">Horario</a></li>',
    '<li class="nav-item active"><a class="nav-link" href="cal.php">Horario</a></li>', $navbar);
session_start();
if (isset($_SESSION['username'])&&isset($_SESSION['user_id'])) {
	$navbar_res = str_replace('list_diplomas.php',
	    'list_diplomas.php?user_id='.$_SESSION['user_id'], $navbar);
	$result = str_replace("##navbar##", $navbar_res, $template);
	$result = str_replace("##username##", $_SESSION['username'], $result);
	$result = str_replace("mycal.php", 'mycal.php?user_id='.$_SESSION['user_id'], $result);
	//Avisos:
	if (isset($_GET["created_class"])) {
		$result = str_replace('##avisos##', '<script>alert("¡Clase correctamente creada!")</script>', $result);
	} else if (isset($_GET["joined_class"])) {
		$result = str_replace('##avisos##', '<script>alert("¡Inscrito correctamente!")</script>', $result);
	} else if (isset($_GET["transfered_diploma"])) {
		$result = str_replace("##avisos##", "<script>alert('¡Diploma entregado! Su nuevo secreto es: ".$_GET['transfered_diploma']."')</script>", $result);
		//Observación: en el caso de transfered_diploma, la variable GET podría utilizarse para alterar el mensaje o mostrar mensajes ajenos en nombre de la página
	} else {
		$result = str_replace('##avisos##', '', $result);
	}
	print($result);
} else {
	$result = str_replace("##navbar##", $navbar, $template);
	$result = str_replace("##username##", '¡No te has identificado!', $result);
	$result = str_replace('<a class="dropdown-item" href="logout.php">Salir</a>', '<a class="dropdown-item" href="login.php">Indentificarse</a><a class="dropdown-item" href="signup.html">Registrarse</a>', $result);
	$result = str_replace('##avisos##', '', $result);
	$result = str_replace('list_diplomas.php', 'login.php', $result);
	print($result);
}
?>