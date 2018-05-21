<?php
session_start();
if (isset($_SESSION['username'])&&isset($_SESSION['user_id'])) {
	$userId=$_SESSION['user_id'];
	$template = file_get_contents("assets/mycal.html");
	if (isset($_GET['user_id'])) {

		if ($userId!==$_GET['user_id']) { //el usuario cuyo calendario quiere verse es distinto del usuario logueado:
			$template = str_replace("<div id='calendar-container'><div id='calendar'></div></div>",
				"<p class='error'>No tienes permiso para ver el calendario de otro profesor</p>",$template);
		}

		$navbar = file_get_contents("assets/navbar.html");
		$navbar = str_replace( //TODO: Poner el nav-link de la página actual activo
		'<li class="nav-item"><a class="nav-link" href="mycal.php">Horario</a></li>',
		'<li class="nav-item active"><a class="nav-link" href="mycal.php">Horario</a></li>', $navbar);
		$result = str_replace("##navbar##", $navbar, $template);
		if (!isset($_SESSION['username'])) {
			$result = str_replace("##username##", '¡No te has identificado!', $result);
			$result = str_replace('<a class="dropdown-item" href="logout.php">Salir</a>', '<a class="dropdown-item" href="login.php">Indentificarse</a><a class="dropdown-item" href="signup.html">Registrarse</a>', $result);
		} else {
			$result = str_replace("##username##", $_SESSION['username'], $result);
		}
		//Avisos:
		if (isset($_GET["created_class"])) {
			$result = str_replace('##avisos##', '<script>alert("¡Clase correctamente creada!")</script>', $result);
		} else if (isset($_GET["joined_class"])) {
			$result = str_replace('##avisos##', '<script>alert("¡Inscrito correctamente!")</script>', $result);
		} else if (isset($_GET["transfered_diploma"])) {
			$result = str_replace("##avisos##", "<script>alert('¡Diploma entregado! Su nuevo secreto es: ".$_GET['transfered_diploma']."')</script>", $result);
			//Observación: en el caso de transfered_diploma, la variable GET podría utilizarse para alterar el mensaje o mostrar mensajes ajenos en nombre de la página
		} else {
			$result = str_replace('##avisos##', '', $result);
		}
		print($result);

	} else {
		echo 'petición inválida. falta variable GET user_id';
	}

} else {
	header("Location: /cal/login.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>