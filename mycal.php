<?php
session_start();
if (isset($_SESSION['username'])) {
	$template = file_get_contents("assets/mycal.html");
	$navbar = file_get_contents("assets/navbar.html");
	$result = str_replace("##navbar##", $navbar, $template);
	$result = str_replace("##username##", $_SESSION['username'], $result);
	print($result);
} else {
	header("Location: /cal/login.html"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>