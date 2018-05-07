<?php
session_start();
if (isset($_SESSION['username'])) {
	$template = file_get_contents("assets/mycal.html");
	$navbar = file_get_contents("assets/navbar.html");
	$navbar = str_replace(
		'<li class="nav-item">
                <a class="nav-link" href="mycal.php">Tu Calendario</a>',
        '<li class="nav-item active">
                <a class="nav-link" href="mycal.php">Tu Calendario</a>', $navbar);
	$result = str_replace("##navbar##", $navbar, $template);
	if (!isset($_SESSION['username'])) {
		$result = str_replace("##username##", '¡No te has identificado!', $result);
		$result = str_replace('<a class="dropdown-item" href="logout.php">Salir</a>', '<a class="dropdown-item" href="login.html">Indentificarse</a><a class="dropdown-item" href="signup.html">Registrarse</a>', $result);
	} else {
		$result = str_replace("##username##", $_SESSION['username'], $result);
	}
	print($result);
} else {
	header("Location: /cal/login.html"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>