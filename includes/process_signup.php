<?php
include 'db_connect.php';
include 'functions.php';
$error_msg = "";
if (isset($_POST['username'], $_POST['email'], $_POST['hashed_password'])) {
    // Sanear y validar los datos recibidos
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $hashed_password = filter_input(INPUT_POST, 'hashed_password', FILTER_SANITIZE_STRING);

    if (validate_signup($username,$email,$hashed_password,$pdo) == true) {
        create_user($username,$email,$hashed_password,$pdo);
        echo 'registrado!'; //TODO: redirigir a mycal.html
        exit();
    } else { 
		// No se han enviado las variables POST correctas
		echo 'petición inválida';
	}
?>