<?php
/*
He organizado este .php de funciones separándolo en varios ficheros:
 - functions_create.php
 - functions_login.php
 - functions_tlmcoin.php
 - functions_vobj.php
Están separados por funcionalidades para hacerlo más manejable.
*/

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
?>
