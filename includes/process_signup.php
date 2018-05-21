<?php
include 'db_connect.php';
include 'functions_create.php';
include 'functions_login.php';

session_start();

if (isset($_POST['username'], $_POST['email'], $_POST['tlmcoin'], $_POST['hashed_password'])) {
    // Sanear y validar los datos recibidos
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);;
    $tlmcoin = filter_input(INPUT_POST, 'tlmcoin', FILTER_SANITIZE_STRING);
    $hashed_password = filter_input(INPUT_POST, 'hashed_password', FILTER_SANITIZE_STRING);

    $res = validate_signup($username,$email,$tlmcoin,$pdo);
    if ($res===true) {
        //TODOn: validar tlmcoin
        create_user($username,$email,$tlmcoin,$hashed_password,$pdo);
        if(login($username,$hashed_password,$pdo)) {
            header("Location: /cal/mycal.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
        }
    } else { 
        header("Location: /cal/signup.php?error=".$res); // IMPORTANTE: se ha usado /cal/ como parte de la url
	}
}
?>