<?php
function validate_signup($username, $email, $tlmcoin, $pdo) {
    $prep_stmt = "SELECT id FROM users WHERE email = :e LIMIT 1";
    $stmt = $pdo->prepare($prep_stmt);

    if ($stmt) {
        $stmt->bindParam(':e', $email);
        $stmt->execute();
        $existe=false;
        while($res=$stmt->fetch(PDO::FETCH_ASSOC)) { $existe = $res['id'];} // para saber si ya existía ese email
        if ($existe) {
            $error = 'ya existe un usuario con este email!';
            return $error;
        }
    }

    $prep_stmt = "SELECT id FROM users WHERE username = :u LIMIT 1";
    $stmt = $pdo->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bindParam(':u', $username);
        $stmt->execute();
        $existe=false;
        while($res=$stmt->fetch(PDO::FETCH_ASSOC)) { $existe = $res['id'];} // para saber si ya existía ese username
        if ($existe) {
            $error = 'ya existe un usuario con este username!';
            return $error;
        }
    }

    $prep_stmt = "SELECT id FROM users WHERE tlmcoin = :t LIMIT 1";
    $stmt = $pdo->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bindParam(':t', $tlmcoin);
        $stmt->execute();
        $existe=false;
        while($res=$stmt->fetch(PDO::FETCH_ASSOC)) { $existe = $res['id'];} // para saber si ya existía ese monedero
        if ($existe) {
            $error = 'ya existe un usuario con este monedero tlmCoin!';
            return $error;
        }
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

function create_class($course, $lesson, $price, $datetimeStart, $datetimeEnd, $pdo) {
    /*POSIBLE MEJORA: antes de insertar, comprobar:
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
            $genDiploma = vobj_crea($classId,$userId,$teacherCoin,$teacherCoinSecret);
            $genDiplomaOid=$genDiploma['oid'];
            $genDiplomaSecret=$genDiploma['secret'];

            if ($insertD_stmt = $pdo->prepare("UPDATE classes SET diploma_oid=:o, diploma_secret=:s WHERE id=:i")) {
                $insertD_stmt->bindParam(':o', $genDiplomaOid);
                $insertD_stmt->bindParam(':s', $genDiplomaSecret);
                $insertD_stmt->bindParam(':i', $classId);
                // Ejecutar la query preparada
                if ($insertD_stmt->execute()) {
                    if (create_event($dateTime,$classId,$course,$userId,$teacher,$pdo)) {
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
            $error = 'error al obtener el monedero tlmCoin del usuario';
            return $error;
            die($error);
        }
    }
    $teachCoin = parse_coin($teacherId,$pdo);
    $teacherCoin = $teachCoin[0];
    $teacherCoinSecret = $teachCoin[1];
    if (transfer_coin($studentCoin,$teacherCoin,$studentCoinSecret,$price)) {
        // 2. Crear NUEVO DIPLOMA:
        if ($stmt = $pdo->prepare('SELECT course,diploma_oid, diploma_secret FROM classes WHERE id=:c')) {
            $stmt->bindParam(':c', $classId);
            // Ejecutar la query preparada
            if ($stmt->execute()) {
                list($course,$genDiplomaOid,$genDiplomaSecret) = $stmt->fetch(PDO::FETCH_NUM);
            } else {
                $error = 'error en SELECT el oid y secret del nuevo diploma';
                return $error;
                die($error);
            }
        }

        $newDiploma = vobj_nuevo($genDiplomaOid,$genDiplomaSecret,$teacherCoin,$teacherCoinSecret);

        // Insertar el nuevo diploma en la base de datos
        if ($insert_stmt = $pdo->prepare("INSERT INTO diplomas (diploma_oid,diploma_secret,user_id,class_id) VALUES (:o, :s, :u, :c)")) {
            $insert_stmt->bindParam(':o', $newDiploma['oid']);
            $insert_stmt->bindParam(':s', $newDiploma['secret']);
            $insert_stmt->bindParam(':u', $userId);
            $insert_stmt->bindParam(':c', $classId);
            // Ejecutar la query preparada
            if ($insert_stmt->execute()) {
                $teacher = 0;
                if (create_event($dateTime,$classId,$course,$userId,$teacher,$pdo)){
                    return true;
                }
            } else {
                $error = 'error en INSERT INTO diplomas';
                return $error;
                die($error);
            }
        }
    } else {
        $error = 'error en el pago de alumno a profesor';
        return $error;
        die($error);
    }
}

function create_event($dateTime,$classId,$course,$userId,$teacher,$pdo) {
    // Insertar el evento actual en la base de datos
    if ($insert_stmt = $pdo->prepare("INSERT INTO events (date_time, class_id, course, user_id, teacher) VALUES (:d, :c, :o, :u, :t)")) {
        $insert_stmt->bindParam(':d', $dateTime);
        $insert_stmt->bindParam(':c', $classId);
        $insert_stmt->bindParam(':o', $course);
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
            // Imprimir el nuevo secreto del diploma ya entregado
            return json_encode($newDiploma);
        } else {
            echo 'error en INSERT INTO diploma entregado';
            return false;
        }
    }
}

?>
