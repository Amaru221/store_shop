<?php
require_once "leer_configuracion.php";

if($_SERVER['REQUEST_METHOD'] === "POST" and isset($_POST['user']) and isset($_POST['correo1']) and isset($_POST['clave1'])){

    $res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
    $bd = new PDO($res[0], $res[1], $res[2]);
    $usuario = $_POST['user'];
    $correo = $_POST['correo1'];
    $clave = $_POST['clave1'];
    //encriptamos contraseña con BCRYPT
    $claveEncrypt = password_hash($clave, PASSWORD_BCRYPT);

    $ins1 = "select * from usuario where correo = '$correo'";

    $resul = $bd->query($ins1);
    if($resul->rowCount() === 1){
        echo 0;
    }else{
        $bd->beginTransaction();
        $ins = "insert into usuario (nombre, correo, clave) values ('$usuario', '$correo', '$claveEncrypt')";
        $result = $bd->query($ins);
        if(!$result){
            $bd->rollback();
            echo 2;
        }else{         
            $bd->commit();
            echo 1;
        }
    }

}else{
    echo 3;
}
?>