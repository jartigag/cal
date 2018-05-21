<?php

$template = file_get_contents("assets/signup.html");
$navbar = file_get_contents("assets/navbar.html");
//Avisos:
if (isset($_GET["error"])) {
	$result = str_replace("##avisos##", "<script>alert('Error en la inscripción: ".$_GET['error']."')</script>", $template);
	//Observación - problema de seguridad: esta variable GET podría utilizarse para alterar el mensaje o mostrar mensajes ajenos en nombre de la página
} else {
	$result = str_replace('##avisos##', '', $template);
}
print($result);
?>