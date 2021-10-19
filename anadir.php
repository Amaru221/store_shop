<?php
require_once "sesiones.php";
require_once "leer_configuracion.php";
comprobar_sesion();


$res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
$bd = new PDO($res[0], $res[1], $res[2]);

if($_SERVER['REQUEST_METHOD'] === "GET" and isset($_GET['codProd']) and isset($_GET['unidades'])){
    $codProd = $_GET['codProd'];
    $unidades = $_GET['unidades'];
    
    if(isset($_SESSION['carrito'][$codProd])){
        $unit = ($_SESSION['carrito'][$codProd]+$unidades);
        $ins = "select stock from producto where codProd = $codProd and stock>=".$unit;
        $resul = $bd->query($ins);
        if($resul->rowCount() >= 1){
            $_SESSION['carrito'][$codProd]+=$unidades;
            echo 1;
        }else{
            echo 2;
        }
        
    }else{   
        $ins = "select stock from producto where codProd = $codProd and stock>=".$unidades;
        $resul = $bd->query($ins);
        if($resul->rowCount() >= 1){
            $_SESSION['carrito'][$codProd] = $unidades;
            echo 1;
        }else{
            echo 2;
        }
    }
}else{
    echo 0;
}
?>