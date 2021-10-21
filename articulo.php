<?php
require_once "leer_configuracion.php";
require_once "sesiones.php";
comprobar_sesion();

if($_SERVER['REQUEST_METHOD'] == "GET" and $_GET['codProd']){
    echo $_GET['codProd'];
}


?>