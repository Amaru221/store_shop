<?php
require_once "leer_configuracion.php";
session_start();

if($_SERVER['REQUEST_METHOD'] === "GET" and isset($_GET['correo']) and isset($_GET['clave'])){
    $res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
    $bd = new PDO($res[0], $res[1], $res[2]);

    $correo = $_GET['correo'];
    $clave = $_GET['clave'];
    $ins = "select nombre, clave from usuario where correo = '$correo'";
    $resul = $bd->query($ins);

    if(!$resul){
        echo 0;
    }else if ($resul->rowCount() === 0) {   
        echo 0;
    }else{
            $salida = $resul->fetch();
            $user = $salida[0];
            $passwordEncrypt = $salida[1];
            if(password_verify($clave, $passwordEncrypt)){
                $_SESSION['usuario'] = $user;
                $_SESSION['correo'] = $correo;
                $_SESSION['carrito'] = [];
                echo 1;
            }else{
                echo 0;
            }
    }

}else{
    header("Location: login.html");
}

?>