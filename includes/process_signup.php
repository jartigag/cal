<?php
include 'db_connect.php';
include 'functions.php';

session_start();

if (isset($_POST['username'], $_POST['email'], $_POST['hashed_password'])) {
    // Sanear y validar los datos recibidos
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $hashed_password = filter_input(INPUT_POST, 'hashed_password', FILTER_SANITIZE_STRING);

    if (validate_signup($username,$email,$hashed_password,$pdo) == true) {
        create_user($username,$email,$hashed_password,$pdo);
        if(login($username,$hashed_password,$pdo) == true) {
            header("Location: /cal/mycal.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
        }
    } else { 
		// No se han enviado las variables POST correctas
		echo 'petición inválida';
	}
}
?>