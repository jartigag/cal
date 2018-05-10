<?php
function login($username, $hashed_password, $pdo) {
	if ($stmt = $pdo->prepare("SELECT id, password,random_salt FROM users WHERE username = :u LIMIT 1")) {
 		$stmt->bindParam(':u', $username);
 		// Ejecutar la query preparada
		$stmt->execute();
		
		$user_row = $stmt->fetch(PDO::FETCH_NUM); // PDO::FETCH_NUM porque "list() only works on numerical arrays and assumes the numerical indices start at 0."
		list($user_id, $db_password, $db_random_salt) = $user_row; // para asignar todas las variables necesarias de golpe desde $user_row
		
		$num_rows = $stmt->fetchColumn();
		$num_rows = 1; //TODO: usar $stmt->fetchColumn() realmente
		if($num_rows == 1) { // Si el usuario existe:
			// Crear contraseña hasheada con la semilla
			$hashed2_password = hash('sha1',$hashed_password.$db_random_salt); // Durante el registro, la contraseña se hasheó 2 veces con sha1 (una en js, otra en php con $db_random_salt) y se almacenó el resultado
			if($db_password == $hashed2_password) { // Comprobar si coinciden la contraseña dada con la de la base de datos
				// Contraseña correcta:
				//$ip_address = $_SERVER['REMOTE_ADDR']; // IP del usuario 
				//$user_browser = $_SERVER['HTTP_USER_AGENT']; // User-Agent del usuaraio
				//$user_id = preg_replace("/[^0-9]+/", "", $user_id);
				$_SESSION['user_id'] = $user_id; 
				//$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
				$_SESSION['username'] = $username;
				$_SESSION['login_string'] = hash('sha1', $password); // para comprobar luego si está logeado (originalmente era $password.$ip_address.$user_browser)
				// Login correcto.
				return true;    
			} else {
				// Contraseña incorrecta.
				// Grabar el intento de login en la base de datos
				$now = date('Y-m-d H:i:s');
				$pdo->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
				return false;
			}
		} else {
			// No existe el usuario.
			return false;
		}
	 }
}

function checkbrute($user_id, $pdo) {
	// Timestamp actual
	$now = time();
	// Intentos de login en las últimas dos horas
	$valid_attempts = $now - (2 * 60 * 60);
 
	if ($stmt = $pdo->prepare("SELECT time FROM login_attempts WHERE user_id = :i AND time > '$valid_attempts'")) { 
		$stmt->bindParam(':i', $user_id);
		$stmt->execute();
		$num_rows = $stmt->fetchColumn();
		// Si hay más de 5 logins inválidos:
		if($num_rows > 5) {
			 return true;
		} else {
			 return false;
		}
	 }
}

/*function login_check($pdo) {
	// Comprobar si se tienen todas las variables de sesión
	if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
		$user_id = $_SESSION['user_id'];
		$login_string = $_SESSION['login_string'];
		$username = $_SESSION['username'];
		//$ip_address = $_SERVER['REMOTE_ADDR']; // IP del usuario
		//$user_browser = $_SERVER['HTTP_USER_AGENT']; // User-Agent del usuario
 
		if ($stmt = $pdo->prepare("SELECT password FROM users WHERE id = ? LIMIT 1")) { 
			$stmt->bindParam('i', $user_id);
			$stmt->execute();
			$num_rows = $stmt->fetchColumn();
			if($stmt->num_rows == 1) { // Si el usuario existe:
				//TODO: REVISAR
				$stmt->bind_result($password); // Recuperar variables del resultado
				$stmt->fetch();
				$login_check = hash('sha1', $password); // para comprobar luego si está logeado (originalmente era $password.$ip_address.$user_browser)
				if($login_check == $login_string) {
					// Logeado
					return true;
				} else {
					// No logeado
					return false;
				}
			} else {
				// No logeado
				return false;
			}
		} else {
			// No logeado
			return false;
		}
	} else {
		// No logeado
		return false;
	}
}*/
?>
