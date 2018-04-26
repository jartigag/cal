<?php

function sec_session_start() {
	$session_name = 'sec_session_id'; // Set a custom session name
	$secure = false; // Set to true if using https.
	$httponly = true; // This stops javascript being able to access the session id. 

	ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
	$cookieParams = session_get_cookie_params(); // Gets current cookies params.
	session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
	session_name($session_name); // Sets the session name to the one set above.
	session_start(); // Start the php session
	session_regenerate_id(true); // regenerated the session, delete the old one.     
}

function validate_signup($username, $email, $password, $pdo) {
    $prep_stmt = "SELECT id FROM users WHERE email = :e LIMIT 1";
    $stmt = $pdo->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bindParam(':e', $email);
        $stmt->execute();
        $num_rows = $stmt->fetchColumn();
        if ($num_rows == 1) {
            echo 'ya existe un usuario con este email!';
            return false;
        }
    } else {
        echo 'error de la base de datos';
        return false;
    }
    return true;
}

function create_user($username, $email, $password, $pdo) {
	// Crear una semilla aleatoria
	$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
	// Crear contraseña hasheada con la semilla
	$password = hash('sha512', $password . $random_salt);
	// Insertar el nuevo usuario en la base de datos
	if ($insert_stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:u, :e, :p)")) {
	    $insert_stmt->bindParam(':u', $username);
	    $insert_stmt->bindParam(':e', $email);
	    $insert_stmt->bindParam(':p', $password); //TODO: $random_salt sin almacenar de momento!
	    // Ejecutar la query preparada
	    if (!$insert_stmt->execute()) {
	    	echo 'error con la query INSERT INTO';
	        exit();
	    }
	}
}

function login($username, $password, $pdo) {
	 // "Using prepared Statements means that SQL injection is not possible."
	 // https://www.w3schools.com/php/php_mysql_prepared_statements.asp
	 if ($stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :u LIMIT 1")) {
	 		$stmt->bindParam(':u', $username);
			$stmt->execute(); // Execute the prepared query.
			$num_rows = $stmt->fetchColumn();
			if($num_rows == 1) { // Si el usuario existe:
					if($db_password == $password) { // Comprobar si coinciden la contraseña dada con la de la base de datos
						// Contraseña correcta:
						//$ip_address = $_SERVER['REMOTE_ADDR']; // IP del usuario 
						//$user_browser = $_SERVER['HTTP_USER_AGENT']; // User-Agent del usuaraio
						//$user_id = preg_replace("/[^0-9]+/", "", $user_id);
						$_SESSION['user_id'] = $user_id; 
						//$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
						$_SESSION['username'] = $username;
						$_SESSION['login_string'] = hash('sha512', $password); // para comprobar luego si está logeado (originalmente era $password.$ip_address.$user_browser)
						// Login correcto.
						return true;    
					} else {
						// Contraseña incorrecta.
						// Grabar el intento de login en la base de datos
						$now = time();
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

function login_check($pdo) {
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
				$login_check = hash('sha512', $password); // para comprobar luego si está logeado (originalmente era $password.$ip_address.$user_browser)
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
}

?>