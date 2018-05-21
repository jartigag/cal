<?php
session_start();

$template = file_get_contents("assets/stats.html");
$navbar = file_get_contents("assets/navbar.html");
$navbar = str_replace( // Poner el nav-link de la página actual activo
	'<li class="nav-item"><a class="nav-link" href="stats.php">Estadísticas</a></li>',
    '<li class="nav-item active"><a class="nav-link" href="stats.php">Estadísticas</a></li>', $navbar);
$result = str_replace("##navbar##", $navbar, $template);

if (isset($_SESSION['username'])&&isset($_SESSION['user_id'])) {
	$result = str_replace("##username##", $_SESSION['username'], $result);
	$result = str_replace("mycal.php", 'mycal.php?user_id='.$_SESSION['user_id'], $result);
	$result = str_replace('list_diplomas.php',
	    'list_diplomas.php?user_id='.$_SESSION['user_id'], $result);
} else {
	$result = str_replace("##username##", '¡No te has identificado!', $result);
	$result = str_replace('<a class="dropdown-item" href="logout.php">Salir</a>', '<a class="dropdown-item" href="login.php">Indentificarse</a><a class="dropdown-item" href="signup.html">Registrarse</a>', $result);
	$result = str_replace('list_diplomas.php', 'login.php', $result);
}
print($result);
?>