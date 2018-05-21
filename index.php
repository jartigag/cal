<?php
session_start();

$template = file_get_contents("assets/index.html");
$navbar = file_get_contents("assets/navbar.html");
$result = str_replace( // Poner el nav-link de la página actual activo
	'<li class="nav-item"><a class="nav-link" href="index.php">Inicio</a>',
    '<li class="nav-item active"><a class="nav-link" href="index.php">Inicio</a>', $navbar);
	
if (isset($_SESSION['username'])&&isset($_SESSION['user_id'])) {
	$navbar_res = str_replace('list_diplomas.php',
	    'list_diplomas.php?user_id='.$_SESSION['user_id'], $result);
	$result = str_replace("##navbar##", $navbar_res, $template);
	$result = str_replace("##username##", $_SESSION['username'], $result);
	$result = str_replace("mycal.php", 'mycal.php?user_id='.$_SESSION['user_id'], $result);
} else {
	$result = str_replace("##username##", '¡No te has identificado!', $result);
	$result = str_replace("##navbar##", $result, $template);
	$result = str_replace("mycal.php", 'login.php', $result);
	$result = str_replace('<a class="dropdown-item" href="logout.php">Salir</a>',
		'<a class="dropdown-item" href="login.php">Indentificarse</a><a class="dropdown-item" href="signup.html">Registrarse</a>', $result);
	$result = str_replace('list_diplomas.php', 'login.php', $result);
}

print($result);

?>