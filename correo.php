<?php
use PHPMailer\PHPMailer\PHPMailer;
require dirname(__FILE__)."./vendor/autoload.php";
require_once "leer_configuracion.php";

function cargar_productos($codigosProductos){
	$res = leer_config(dirname(__FILE__)."/src/configuracion.xml", dirname(__FILE__)."/src/configuracion.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);
	$texto_in = implode(",", $codigosProductos);
	$ins = "select * from producto where codProd in($texto_in)";
	$resul = $bd->query($ins);
	if (!$resul) {
		return FALSE;
	}
	return $resul;
}


function enviar_correos($carrito, $pedido, $correo){
	if(count($carrito) <= 0){
		return FALSE;
	}else{
		$cuerpo = crear_correo($carrito, $pedido, $correo);
		return enviar_correo_multiples("$correo, pedidos@empresafalsa.com", 
                        	$cuerpo, "Pedido $pedido confirmado");
	}
								
}
function crear_correo($carrito, $pedido, $correo){
	$texto = "<h1>Pedido nº $pedido </h1><h2>Usuario: $correo </h2>";
	$texto .= "Detalle del pedido:";
	$productos = cargar_productos(array_keys($carrito));
	$precioTotal = 0;
	$texto .= "<table>"; //abrir la tabla
	$texto .= "<tr><th>Nombre</th><th>Descripción</th><th>Peso</th><th>Unidades</th><th>Precio</th></tr>";
	foreach($productos as $producto){
		$cod = $producto['codProd'];
		$nom = $producto['nombre'];
		$des = $producto['descripcion'];
		$peso = $producto['peso'];
        $precio = $producto['precio'];
		$unidades = $_SESSION['carrito'][$cod];							    
		$texto .= "<tr><td>$nom</td><td>$des</td><td>$peso</td><td>$unidades</td><td>$precio eur. </td>
		<td> </tr>";
		$precioTotal+=$precio;
	}
	$texto.="<tr><td>Precio Total: </td><td>$precioTotal eur.</td></tr>";
	$texto .= "</table>";	
	return $texto;
}
function enviar_correo_multiples($lista_correos,  $cuerpo,  $asunto = "Orden de pedido"){
		$res = leer_configCorreo(dirname(__FILE__)."/src/config_correo.xml", dirname(__FILE__)."/src/config_correo.xsd");
		$mail = new PHPMailer();		
		$mail->IsSMTP(); 					
		$mail->SMTPDebug = 0;  // cambiar a 1 o 2 para ver errores
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "tls";
		$mail->Host = "smtp.gmail.com";
		$mail->Port = $res[2];      
		$mail->Username = $res[0];  //usuario de gmail
		$mail->Password = $res[1]; //contraseña de gmail          
		$mail->SetFrom('noreply@empresafalsa.com', 'Sistema de pedidos Store_Shop');
		$mail->Subject = $asunto;
		$mail->MsgHTML($cuerpo);

		/*partir la lista de correos por la coma*/
		$correos = explode(",", $lista_correos);
		foreach($correos as $correo){
			$mail->AddAddress($correo, $correo);
		}
		if(!$mail->Send()) {
		  return $mail->ErrorInfo;
		} else {
		  return TRUE;
		}
}

?>