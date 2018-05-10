<?php
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
?>
