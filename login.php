<?php
session_start();

$template = file_get_contents("login.html");
$navbar = file_get_contents("assets/navbar.html");
if (isset($_GET["error"])) {
	$result = str_replace("##mensajeError##", "Usuario o contraseña incorrectos", $template);
} else {
	$result = str_replace("##mensajeError##", "", $template);
}

print($result);
?>