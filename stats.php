<?php
session_start();

$template = file_get_contents("stats.html");
$navbar = file_get_contents("assets/navbar.html");
$navbar = str_replace( //TODO: no está funcionando aquí.. Poner el nav-link de la página actual activo
	'<li class="nav-item">
			<a class="nav-link" href="stats.php">Estadísticas</a>',
    '<li class="nav-item active">
			<a class="nav-link" href="stats.php">Estadísticas</a>', $navbar);
$result = str_replace("##navbar##", $navbar, $template);
	
if (isset($_SESSION['username'])) {
	
	$result = str_replace("##username##", $_SESSION['username'], $result);


} 
else{
		$result = str_replace("##username##", '¡No te has identificado!', $result);
		$result = str_replace('<a class="dropdown-item" href="logout.php">Salir</a>', '<a class="dropdown-item" href="login.html">Indentificarse</a><a class="dropdown-item" href="signup.html">Registrarse</a>', $result);
}

	print($result);
?>