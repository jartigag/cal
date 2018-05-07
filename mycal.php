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
	if ($_SESSION['username']=="") {
		$result = str_replace("##username##", '¡No te has identificado! <a class="p-2 text-white" href="login.html">Identifícate</a><a class="p-2 text-white" href="signup.html">o Regístrate</a>', $result);
	} else {
		$result = str_replace("##username##", '<a class="p-2 text-white" href="#">'.$_SESSION['username'].'</a><a class="p-2 text-white" href="logout.php">(Salir)</a>', $result);
	}
	print($result);
} else {
	header("Location: /cal/login.html"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>