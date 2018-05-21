<?php
include '../includes/db_connect.php';
session_start();

$id = $_POST['id'];
$datetime_start = $_POST['datetime_start'];
$datetime_end = $_POST['datetime_end'];
$query = "UPDATE classes SET datetime_start=?,datetime_end=? WHERE id=?";
$stmt = $pdo->prepare($query);
$stmt->execute(array($datetime_start,$datetime_end,$id));

?>
