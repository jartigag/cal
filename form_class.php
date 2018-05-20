<?php
session_start();
	
if (isset($_SESSION['username'])) {
	$template = file_get_contents("form_class.html");
	$navbar = file_get_contents("assets/navbar.html");
	$navbar = str_replace( // Poner el nav-link de la página actual activo
		'<li class="nav-item"><a class="nav-link" href="form_class.php">Crear clase</a></li>',
		'<li class="nav-item active"><a class="nav-link" href="form_class.php">Crear clase</a></li>', $navbar);
	$result = str_replace("##navbar##", $navbar, $template);
	$result = str_replace("##username##", $_SESSION['username'], $result);
	print($result);
} else {
	header("Location: /cal/login.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>