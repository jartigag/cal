<?php
function vobj_crea($classId,$userId,$teacherCoin,$teacherCoinSecret) {
	$r=file_get_contents('https://webalumnos.tlm.unavarra.es:10169/vobj/crea.php?nombre=diploma&desc='.$classId.'&icon=&propietario='.$userId.'&generador=1&coin='.$teacherCoin.'-'.$teacherCoinSecret); //TODO: mejorable

	//TODO: controlar errores
	return json_decode($r,true);;
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
?>
