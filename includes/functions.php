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

function create_user($username, $email, $tlmcoin, $hashed_password, $pdo) {
	// Crear una semilla aleatoria
	// uso sha1 simplemente para que el hash no quede tan largo en la tabla
	$random_salt = hash('sha1', uniqid(openssl_random_pseudo_bytes(16), TRUE));
	// Crear contraseña hasheada con la semilla
	$hashed2_password = hash('sha1', $hashed_password.$random_salt);
	// Insertar el nuevo usuario en la base de datos
	if ($insert_stmt = $pdo->prepare("INSERT INTO users (username, email, tlmcoin, password, random_salt) VALUES (:u, :e, :t, :p, :s)")) {
	    $insert_stmt->bindParam(':u', $username);
	    $insert_stmt->bindParam(':e', $email);
	    $insert_stmt->bindParam(':t', $tlmcoin);
	    $insert_stmt->bindParam(':p', $hashed2_password);
	    $insert_stmt->bindParam(':s', $random_salt);
	    // Ejecutar la query preparada
	    if (!$insert_stmt->execute()) {
	    	echo 'error con la query en create_user';
	    	print_r($pdo->errorInfo());
	    }
	}
}

function validate_coin($userId,$price,$pdo) {
	$parsed_coin = parse_coin($userId,$pdo);
	$coinid = $parsed_coin[0];

	$r = file_get_contents('https://coin.tlm.unavarra.es/api/status.php?coinid='.$coinid);
	$value = json_decode($r,true)['value'];

	if ($value>=$price) {
		return true;
	} else {
		return false;
	}
}

function transfer_coin($srcid,$dstid,$auth,$value) {
	$r=file_get_contents('https://coin.tlm.unavarra.es/api/transfer.php?srcid='.$srcid.'&dstid='.$dstid.'&auth='.$auth.'&value='.$value);
	$response=json_decode($r,true)['ok'];

	return $response;
}

function parse_coin($userId,$pdo) { //TODO: reescribir para no tener que pasar $pdo
	if ($stmt = $pdo->prepare('SELECT tlmcoin FROM users WHERE id= :i')) {
 		$stmt->bindParam(':i', $userId);
 		// Ejecutar la query preparada
 		if ($stmt->execute()) {
 			list($coin) = $stmt->fetch(PDO::FETCH_NUM);

 			$parsed_coin = explode("-", $coin); // variable $coin parseada (separada por '-' y guardada en array)

 			return $parsed_coin;
 		} else {
 			die('error al obtener el monedero tlmCoin del usuario');
 		}
	}

}

function create_class($course, $lesson, $price, $datetimeStart, $datetimeEnd, $pdo) {
	/*TODO: antes de insertar, comprobar:
		- que las fechas sean válidas
		- que datetimeStart < datetimeEnd
	*/

    //INSERT INTO classes (diploma_oid, diploma_secret) values (oid,secret)
	// Insertar la nueva clase en la base de datos
	$userId = $_SESSION['user_id'];
	if ($insertC_stmt = $pdo->prepare("INSERT INTO classes (course,lesson,price,datetime_start,datetime_end) VALUES (:c, :l, :p, :s, :e)")) {
	    $insertC_stmt->bindParam(':c', $course);
	    $insertC_stmt->bindParam(':l', $lesson);
	    $insertC_stmt->bindParam(':p', $price);
	    $insertC_stmt->bindParam(':s', date('Y-m-d H:i:s',strtotime($datetimeStart)));
	    $insertC_stmt->bindParam(':e', date('Y-m-d H:i:s',strtotime($datetimeEnd)));
	    // Ejecutar la query preparada
	    if ($insertC_stmt->execute()) {
	    	$dateTime = date('Y-m-d H:i:s');
	    	$teacher = true;
	    	$classId = $pdo->lastInsertId();

		    // Crear DIPLOMA GENERADOR:
		    $teachCoin = parse_coin($userId,$pdo);
		    $teacherCoin = $teachCoin[0];
		    $teacherCoinSecret = $teachCoin[1];
		    $r=file_get_contents('https://webalumnos.tlm.unavarra.es:10169/vobj/crea.php?nombre=diploma&desc='.$classId.'&icon=&propietario='.$userId.'&generador=1&coin='.$teacherCoin.'-'.$teacherCoinSecret); //TODO: mejorable
		    $genDiplomaOid=json_decode($r,true)['oid'];
		    $genDiplomaSecret=json_decode($r,true)['secret'];

		    if ($insertD_stmt = $pdo->prepare("UPDATE classes SET diploma_oid=:o, diploma_secret=:s WHERE id=:i")) {
				$insertD_stmt->bindParam(':o', $genDiplomaOid);
			    $insertD_stmt->bindParam(':s', $genDiplomaSecret);
			    $insertD_stmt->bindParam(':i', $classId);
		        // Ejecutar la query preparada
		        if ($insertD_stmt->execute()) {
		        	if (create_event($dateTime,$classId,$userId,$teacher,$pdo)) {
		        		return true;
		        	}
		        } else {
		        	echo 'error con diploma UPDATE classes';
		        	print_r($pdo->errorInfo());
		        	return false;
		        }
		    }
	    } else {
	    	echo 'error con clase INSERT INTO classes';
	        return false;
	    }
	}
}

function vobj_nuevo($genDiplomaOid,$genDiplomaSecret,$teacherCoin,$teacherCoinSecret) {
	$r=file_get_contents('https://webalumnos.tlm.unavarra.es:10169/vobj/nuevo.php?oid='.$genDiplomaOid.'&secret='.$genDiplomaSecret.'&coin='.$teacherCoin.'-'.$teacherCoinSecret); //TODO: mejorable

	//TODO: controlar errores
	return json_decode($r,true);
}

function vobj_transfiere($diplomaOid,$diplomaSecret,$propietario_dest) {
	$r=file_get_contents('https://webalumnos.tlm.unavarra.es:10169/vobj/transfiere.php?oid='.$diplomaOid.'&secret='.$diplomaSecret.'&propietario='.$propietario_dest);

	//TODO: controlar errores
	return json_decode($r,true);
}

function join_class($dateTime,$classId,$userId,$price,$pdo) {
	// 1. Pagar de Alumno a Profesor:
	$stuCoin = parse_coin($userId,$pdo);
	$studentCoin = $stuCoin[0];
	$studentCoinSecret = $stuCoin[1];
	if ($stmt = $pdo->prepare('SELECT user_id FROM events WHERE class_id = :c AND teacher=1')) {
 		$stmt->bindParam(':c', $classId);
 		// Ejecutar la query preparada
 		if ($stmt->execute()) {
 			list($teacherId) = $stmt->fetch(PDO::FETCH_NUM);
 		} else {
 			die('error al obtener el monedero tlmCoin del usuario');
 		}
	}
	$teachCoin = parse_coin($teacherId,$pdo);
	$teacherCoin = $teachCoin[0];
	$teacherCoinSecret = $teachCoin[1];
	if (transfer_coin($studentCoin,$teacherCoin,$studentCoinSecret,$price)) {
		// 2. Crear NUEVO DIPLOMA:
		if ($stmt = $pdo->prepare('SELECT diploma_oid, diploma_secret FROM classes WHERE id=:c')) {
	 		$stmt->bindParam(':c', $classId);
	 		// Ejecutar la query preparada
	 		if ($stmt->execute()) {
	 			list($genDiplomaOid,$genDiplomaSecret) = $stmt->fetch(PDO::FETCH_NUM);
	 		} else {
	 			die('error al INSERTAR el oid y secret del nuevo diploma');
	 		}
		}

		$newDiploma = vobj_nuevo($genDiplomaOid,$genDiplomaSecret,$teacherCoin,$teacherCoinSecret);

		// Insertar el nuevo diploma en la base de datos
		if ($insert_stmt = $pdo->prepare("INSERT INTO diplomas (diploma_oid,diploma_secret,user_id,class_id) VALUES (:o, :s; :u, :c)")) {
			//TODO: falla. REPASAR
		    $insert_stmt->bindParam(':o', $newDiploma['oid']);
		    $insert_stmt->bindParam(':s', $newDiploma['secret']);
		    $insert_stmt->bindParam(':u', $userId);
		    $insert_stmt->bindParam(':c', $classId);
		    // Ejecutar la query preparada
		    if ($insert_stmt->execute()) {
		    	$teacher = 0;
		    	if (create_event($dateTime,$classId,$userId,$teacher,$pdo)){
		    		return true;
		    	}
		    } else {
		    	echo 'error en INSERT INTO diplomas';
		        return false;
		    }
		}
	} else {
		echo 'error en el pago de alumno a profesor';
	}
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

function transfer_diploma($dateTime,$classId,$propietario_dest,$pdo) {
	//1. obtener oid y secret del diploma de esta clase:
		if ($stmt = $pdo->prepare('SELECT diploma_oid, diploma_secret FROM diplomas WHERE class_id=:c')) {
	 		$stmt->bindParam(':c', $classId);
	 		// Ejecutar la query preparada
	 		if ($stmt->execute()) {
	 			list($diplomaOid,$diplomaSecret) = $stmt->fetch(PDO::FETCH_NUM);
	 		} else {
	 			die('error al obtener el monedero tlmCoin del usuario');
	 		}
		}

	//2. transferir al alumno:
	$newDiploma = vobj_transfiere($diplomaOid,$diplomaSecret,$propietario_dest);

	// 3. insertar el nuevo diploma ENTREGADO en la base de datos
	if ($insert_stmt = $pdo->prepare("INSERT INTO diplomas (diploma_oid,diploma_secret,entregado,date_time,user_id,class_id) VALUES (:o,:s,1,:d,:u,:c)")) {
	    $insert_stmt->bindParam(':o', $newDiploma['oid']);
	    $insert_stmt->bindParam(':s', $newDiploma['secret']);
	    $insert_stmt->bindParam(':d', $dateTime);
	    $insert_stmt->bindParam(':u', $propietario_dest);
	    $insert_stmt->bindParam(':c', $classId);
	    // Ejecutar la query preparada
	    if ($insert_stmt->execute()) {
	    	print(json_encode($newDiploma);
	    	return true;
	    } else {
	    	echo 'error en INSERT INTO diploma entregado';
	        return false;
	    }
	}
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
