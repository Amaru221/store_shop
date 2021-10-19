<?php
function leer_config($nombre, $esquema){
	$config = new DOMDocument();
	$config->load($nombre);
	$res = $config->schemaValidate($esquema);
	if ($res===FALSE){ 
	   throw new InvalidArgumentException("Revise fichero de configuración");
	} 		
	$datos = simplexml_load_file($nombre);
	$ip = $datos->xpath("//ip");
	$nombre = $datos->xpath("//nombre");
	$usu = $datos->xpath("//usuario");
	$clave = $datos->xpath("//clave");	
	$cad = sprintf("mysql:dbname=%s;host=%s", $nombre[0], $ip[0]);
	$resul = [];
	$resul[] = $cad;
	$resul[] = $usu[0];
	$resul[] = $clave[0];
	return $resul;
}

function leer_configCorreo($nombre, $esquema){
	$config = new DOMDocument();
	$config->load($nombre);
	$res = $config->schemaValidate($esquema);
	if($res === FALSE){
		throw new InvalidArgumentException("Revise el fichero de configuración");
	}
	$datos = simplexml_load_file($nombre);
	$user = $datos->xpath("//user");
	$password = $datos->xpath("//password");
	$puerto = $datos->xpath("//puerto");
	$resul = [];
	$resul[] = $user[0];
	$resul[] = $password[0];
	$resul[] = $puerto[0];

	return $resul;
}



?>