<?php
include 'db_connect.php';
include 'functions_create.php';
include 'functions_tlmcoin.php';
include 'functions_vobj.php';

session_start();

if (isset($_SESSION['user_id'])) {
	if (isset($_POST['user_id'],$_POST['class_id'])) {
		$classId = $_POST['class_id'];
		$propietario_dest = $_POST['user_id'];
		$dateTime = date('Y-m-d H:i:s');
		try  {
			$stmt = $pdo->prepare("SELECT teacher FROM events WHERE user_id=".$_SESSION['user_id']." AND class_id=".$classId." limit 1");
			$stmt->execute();
			$teacher=0;
			while($res=$stmt->fetch(PDO::FETCH_ASSOC)) { $teacher = $res['teacher'];} // para saber si el usuario es profesor de esta clase
			if ($teacher) { //si el usuario es el profesor de esta clase:
				$newDiploma = transfer_diploma($dateTime,$classId,$propietario_dest,$pdo);
				if ($newDiploma!==false) {
					header("Location: /cal/mycal.php?transfered_diploma=".$newDiploma); // IMPORTANTE: se ha usado /cal/ como parte de la url
				}
			} else { //si el usuario no es el profesor de esta clase:
				echo 'petición inválida: no eres profesor de esta clase';
			}
		} catch (Exception $e) {
			throw $e;
		}
	} else { 
		// No se ha enviado la variable POST correcta
		echo "petición inválida";
	}
} else {
	header("Location: /cal/login.php"); // IMPORTANTE: se ha usado /cal/ como parte de la url
}
?>