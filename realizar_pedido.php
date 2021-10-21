<?php
require_once "sesiones.php";
require_once "leer_configuracion.php";
require_once "correo.php";
comprobar_sesion();

function insertar_pedido($carrito, $codUser){
	$res = leer_config(dirname(__FILE__)."/src/configuracion.xml", dirname(__FILE__)."/src/configuracion.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);
	$bd->beginTransaction();	
	$hora = date("Y-m-d H:i:s", time());
	// insertar el pedido
	$sql = "insert into pedido(fecha, enviado, id_user)
			values('$hora',0, $codUser)";
	$resul = $bd->query($sql);	
	if (!$resul) {
		return FALSE;
	}
	// coger el id del nuevo pedido para las filas detalle
	$pedido = $bd->lastInsertId();
	// insertar las filas en pedidoproductos
	foreach($carrito as $codProd=>$unidades){
		$sql = "insert into pedidosproductos(codPed, codProd, Unidades)
		             values( $pedido, $codProd, $unidades)";		
		 $resul = $bd->query($sql);	
		if (!$resul) {
			$bd->rollback();
			return FALSE;
		}
		//Actualizamos stock de los productos comprados
		$sql = "update producto set stock = stock-$unidades where CodProd = $codProd";
		$result = $bd->query($sql);
		if(!$result){
			$bd->rollback();
			return FALSE;
		}
	}
	$bd->commit();
	return $pedido;
}



if(isset($_SESSION['carrito']) and $_SERVER['REQUEST_METHOD'] === 'GET' ){
    if(isset($_SESSION['usuario']['correo'])){//count($_SESSION['carrito']) > 0 and 

        $resul = insertar_pedido($_SESSION['carrito'], $_SESSION['usuario']['id']);

			if($resul === FALSE){
				echo 0;	
			}else{

				$correo = $_SESSION['usuario']['correo'];
				//echo 1;				
				$conf = enviar_correos($_SESSION['carrito'], $resul, $correo);				
				if($conf!==TRUE){
					echo 2;
				}else{
                    echo 1;
                }		
				//vaciar carrito
				$_SESSION['carrito'] = [];

			}

    }
}



?>