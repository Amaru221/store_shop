<?php
require_once "sesiones.php";
require_once "leer_configuracion.php";
comprobar_sesion();

if($_SERVER['REQUEST_METHOD'] === "GET"){

    $res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
    $bd = new PDO($res[0], $res[1], $res[2]);

    $ins = "select * from producto";
    $resul = $bd->query($ins);
    $arrayProductos = [];

    if(!$resul or $resul->rowCount() === 0){
        echo 0;
    }else if($resul->rowCount() >=1){
        foreach($resul as $producto){
            $codProd = $producto['codProd'];
            $nombre = $producto['nombre'];
            $desc = $producto['descripcion'];
            $peso = $producto['peso'];
            $stock = $producto['stock'];
            $src = $producto['src'];
            $precio = $producto['precio'];
            $codCat = $producto['codCat'];
            $array = array('codProd' => $codProd, 'nombre' => $nombre, 'descripcion' =>$desc, 'peso'=> $peso, 'stock' => $stock, 'precio' => $precio, 'src' => $src, 'codCat' => $codCat);

            $arrayProductos[] = $array;
        }
        $json = json_encode($arrayProductos);
        echo $json;
    }
    
}




?>