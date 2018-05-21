<?php
session_start();
	
if (isset($_SESSION['username'])&&isset($_SESSION['user_id'])) {
	$template = file_get_contents("assets/form_class.html");
	$navbar = file_get_contents("assets/navbar.html");
	$navbar = str_replace( // Poner el nav-link de la página actual activo
		'<li class="nav-item"><a class="nav-link" href="form_class.php">Crear clase</a></li>',
		'<li class="nav-item active"><a class="nav-link" href="form_class.php">Crear clase</a></li>', $navbar);
	$navbar_res = str_replace('list_diplomas.php',
	    'list_diplomas.php?user_id='.$_SESSION['user_id'], $navbar);
	$result = str_replace("##navbar##", $navbar_res, $template);
	$result = str_replace("##username##", $_SESSION['username'], $result);
	$result = str_replace("mycal.php", 'mycal.php?user_id='.$_SESSION['user_id'], $result);
	print($result);
} else {
	header("Location: /cal/login.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>