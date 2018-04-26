<?php
include 'db_connect.php';
include 'functions.php';
$error_msg = "";
if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
    // Sanear y validar los datos recibidos
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);

    if (validate_signup($username,$email,$password,$pdo) == true) {
        create_user($username, $email, $password,$pdo);
        echo 'registrado!'; //TODO: redirigir a mycal.html
        exit();
    }
}