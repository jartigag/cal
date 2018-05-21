<?php
function login($username, $hashed_password, $pdo) {
	if ($stmt = $pdo->prepare("SELECT id, password,random_salt FROM users WHERE username = :u LIMIT 1")) {
 		$stmt->bindParam(':u', $username);
 		// Ejecutar la query preparada
		$stmt->execute();
		
		$user_row = $stmt->fetch(PDO::FETCH_NUM); // PDO::FETCH_NUM porque "list() only works on numerical arrays and assumes the numerical indices start at 0."
		list($user_id, $db_password, $db_random_salt) = $user_row; // para asignar todas las variables necesarias de golpe desde $user_row
		// Crear contraseña hasheada con la semilla
		$hashed2_password = hash('sha1',$hashed_password.$db_random_salt); // Durante el registro, la contraseña se hasheó 2 veces con sha1 (una en js, otra en php con $db_random_salt) y se almacenó el resultado
		if($db_password == $hashed2_password) { // Comprobar si coinciden la contraseña dada con la de la base de datos
			// Contraseña correcta:
			$_SESSION['user_id'] = $user_id;
			$_SESSION['username'] = $username;
			// Login correcto.
			return true;    
		} else {
			// Contraseña incorrecta.
			// Grabar el intento de login en la base de datos
			$now = date('Y-m-d H:i:s');
			$pdo->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
			return false;
		}
	 }
}

?>
