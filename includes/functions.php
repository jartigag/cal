<?php

// "Using prepared Statements means that SQL injection is not possible."
// https://www.w3schools.com/php/php_mysql_prepared_statements.asp

/*function sec_session_start() { //TODO: mejor que session_start()?
	$session_name = 'sec_session_id'; // Set a custom session name
	$secure = false; // Set to true if using https.
	$httponly = true; // This stops javascript being able to access the session id. 

	ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
	$cookieParams = session_get_cookie_params(); // Gets current cookies params.
	session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
	session_name($session_name); // Sets the session name to the one set above.
	session_start(); // Start the php session
	session_regenerate_id(true); // regenerated the session, delete the old one.     
}*/

function validate_signup($username, $email, $tlmcoin, $pdo) {
	//TODO: comprobar que funciona
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
        return false;
    }

    $prep_stmt = "SELECT id FROM users WHERE username = :u LIMIT 1";
    $stmt = $pdo->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bindParam(':u', $username);
        $stmt->execute();
        $num_rows = $stmt->fetchColumn();
        if ($num_rows == 1) {
            echo 'ya existe un usuario con este username!';
            return false;
        }
    } else {
        return false;
    }

    $prep_stmt = "SELECT id FROM users WHERE tlmcoin = :t LIMIT 1";
    $stmt = $pdo->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bindParam(':t', $tlmcoin);
        $stmt->execute();
        $num_rows = $stmt->fetchColumn();
        if ($num_rows == 1) {
            echo 'ya existe un usuario con este monedero tlmCoin!';
            return false;
        }
    } else {
        return false;
    }

    return true;
}

function create_user($username, $email, $hashed_password, $tlmcoin, $pdo) {
	// Crear una semilla aleatoria
	// uso sha1 simplemente para que el hash no quede tan largo en la tabla
	$random_salt = hash('sha1', uniqid(openssl_random_pseudo_bytes(16), TRUE));
	// Crear contraseña hasheada con la semilla
	$hashed2_password = hash('sha1', $hashed_password.$random_salt);
	// Insertar el nuevo usuario en la base de datos
	if ($insert_stmt = $pdo->prepare("INSERT INTO users (username, email, password, random_salt, tlmcoin) VALUES (:u, :e, :p, :s, :t)")) {
	    $insert_stmt->bindParam(':u', $username);
	    $insert_stmt->bindParam(':e', $email);
	    $insert_stmt->bindParam(':p', $hashed2_password);
	    $insert_stmt->bindParam(':s', $random_salt);
	    $insert_stmt->bindParam(':t', $tlmcoin);
	    // Ejecutar la query preparada
	    if (!$insert_stmt->execute()) {
	    	print_r($pdo->errorInfo());
	    }
	}
}

function validate_coin($userId,$price) {
	//TODO: comprobar que https://coin.tlm.unavarra.es/api/status.php?coinid=X > $price
}

function transfer_coin($srcid,$dstid,$auth,$value) {
	//TODO: https://coin.tlm.unavarra.es/api/transfer.php?srcid=17&dstid=43&auth=ahjsbdy1d189db&value=10
}

function get_coin($userId) {
	//TODO: consultar y devolve tlmcoin (sin secret) del usuario en la tabla users
}

function get_coin_secret($userId) {
	//TODO: consultar y devolve tlmcoin secret del usuario en la tabla users
}

function create_class($course, $lesson, $price, $datetimeStart, $datetimeEnd, $pdo) {
	/*TODO: antes de insertar, comprobar:
		- que las fechas sean válidas
		- que datetimeStart < datetimeEnd
	*/
    /*TODO: Crear DIPLOMA GENERADOR:
    https://webalumnos.tlm.unavarra.es:10169/vobj/crea.php?
    nombre=diploma&
    desc= $classId &
    icon=&
    propietario= $userId &
    generador=1&
    coin= get_coin($userId) 

	>>response: {"oid":"99","secret":"0f6d4550768afbd2"}
    */
    //INSERT INTO classes (diploma_oid, diploma_secret) values (oid,secret)
	// Insertar la nueva clase en la base de datos
	$userId = $_SESSION['user_id'];
	if ($insert_stmt = $pdo->prepare("INSERT INTO classes (course,lesson,price,datetime_start,datetime_end) VALUES (:c, :l, :p, :s, :e)")) {
	    $insert_stmt->bindParam(':c', $course);
	    $insert_stmt->bindParam(':l', $lesson);
	    $insert_stmt->bindParam(':p', $price);
	    $insert_stmt->bindParam(':s', date('Y-m-d H:i:s',strtotime($datetimeStart)));
	    $insert_stmt->bindParam(':e', date('Y-m-d H:i:s',strtotime($datetimeEnd)));
	    $classId = $pdo->lastInsertId()-1; //TODO: vale esto?
	    // Ejecutar la query preparada
	    if ($insert_stmt->execute()) {
	    	$dateTime = date('Y-m-d H:i:s');
	    	$teacher = true;
	    	$classId = $pdo->lastInsertId();
	    	if (create_event($dateTime,$classId,$userId,$teacher,$pdo)){
	    		return true;
	    	}
	    } else {
	    	echo 'error con la query INSERT INTO classes';
	        return false;
	    }
	}
}

function join_class($dateTime,$classId,$userId,$price,$pdo) {
	/*TODO: 
	1. Pagar de Alumno a Profesor:
	$studentCoin = get_coin($userId);
	$studentCoinSecret = get_coin_secret($userId);
	TODO: obtener $teacherId con 'SELECT user_id FROM events WHERE class_id = '.$classId.' AND teacher=1'
	$teacherCoin = get_coin($teacherId);
	if (validate_coin($userId,$price)) {
		if (transfer_coin($studentCoin,$teacherCoin,$studentCoinSecret,$price)) {
			//TODO: 2. Crear NUEVO DIPLOMA:
					'SELECT diploma_oid, diploma_secret FROM classes WHERE id='.$classId

				    https://webalumnos.tlm.unavarra.es:10169/vobj/nuevo.php?
				    oid= $genDiplomaOid &
				    secret= $genDiplomaSecret

					>>response: {"oid":"99","secret":"0f6d4550768afbd2"}
			// TODO: DECIDIR dónde se guardan oid y secret del nuevo diploma. NUEVA TABLA 'diplomas'?
			$teacher = 0;
			if (create_event($dateTime,$classId,$userId,$teacher,$pdo)){
				return true;
			}
		}
	}*/
}

function create_event($dateTime,$classId,$userId,$teacher,$pdo) {
	// Insertar el evento actual en la base de datos
	if ($insert_stmt = $pdo->prepare("INSERT INTO events (date_time, class_id, user_id, teacher) VALUES (:d, :c, :u, :t)")) {
	    $insert_stmt->bindParam(':d', $dateTime);
	    $insert_stmt->bindParam(':c', $classId);
	    $insert_stmt->bindParam(':u', $userId);
	    $insert_stmt->bindParam(':t', $teacher);
	    // Ejecutar la query preparada
	    if ($insert_stmt->execute()) {
	    	return true;
	    } else {
	    	echo 'execute() ha devuelto falso';
	    	print_r($pdo->errorInfo());
	        return false;
	    }
	}
}

function transfer_diploma($dateTime,$propietario_dest,$pdo) {
	//TODO: obtener $oid,$secret con un SELECT FROM de donde corresponda (tabla 'diplomas' quizás?)
	//TODO: https://webalumnos.tlm.unavarra.es:10169/vobj/transfiere.php?oid=$oid&secret=$secret
	//TODO: apuntar diploma como entregado
}

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
