<?php
session_start();

$template = file_get_contents("stats.html");
$navbar = file_get_contents("assets/navbar.html");
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