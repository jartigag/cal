<?php
session_start();
if (isset($_SESSION['username'])) {
	$template = file_get_contents("assets/mycal.html");
	$result = str_replace("##username##", $_SESSION['username'], $template);
	print($result);
} else {
	header("Location: /cal/login.html"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>