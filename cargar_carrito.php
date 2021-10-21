<?php
require_once "sesiones.php";
require_once "leer_configuracion.php";
comprobar_sesion();


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

if($_SERVER['REQUEST_METHOD'] === "GET"){
    $arrayCarrito = $_SESSION['carrito'];
    $productos = cargar_productos(array_keys($arrayCarrito));
    $arrayProductos = [];
    if(count($arrayCarrito)>=1){
        foreach($productos as $producto){
            $codProd = $producto['codProd'];
            $nombre = $producto['nombre'];
            $desc = $producto['descripcion'];
            $peso = $producto['peso'];
            $stock = $producto['stock'];     
            $precio = $producto['precio'];     
            $src = $producto['src'];
            $codCat = $producto['codCat'];
                    
            $array = array('codProd' => $codProd, 'nombre' => $nombre, 'descripcion' =>$desc, 'peso'=> $peso, 'stock' => $stock, 'precio' => $precio, 'src' => $src, 'codCat' => $codCat, 'unidades' => $_SESSION['carrito'][$codProd]);    
            $arrayProductos[] = $array;
        }
        $json = json_encode($arrayProductos);
        echo $json;
    }else{
        echo 0;
    }

    

}
    

?>