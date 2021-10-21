<?php
require "sesiones.php";
require_once "leer_configuracion.php";
comprobar_sesion();

if($_SESSION['usuario']['usuario'] === "admin"){

    if($_SERVER['REQUEST_METHOD'] === "POST" and isset($_POST['name']) and isset($_POST['desc']) && isset($_POST['peso']) and isset($_POST['stock']) and isset($_POST['precio'])){
        $tiempo = time();
        $archivo = $_FILES['image']['name'];
        $tipo = $_FILES['image']['type'];
        $temp = $_FILES['image']['tmp_name'];
        $name = $_POST['name'] ;
        $desc = $_POST['desc'] ;
        $peso = $_POST['peso'] ;
        $stock = $_POST['stock'] ;
        $precio = $_POST['precio'];
        $codCat = $_POST['codCat'];
      //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
        if (!(strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png"))) {
            echo 0;
        }else{
            
            $tmp_name = explode( ".",$archivo);
            $tmp_name = $tmp_name[0].$tiempo.".".$tmp_name[1];
            $archivo = $tmp_name;
            if (move_uploaded_file($temp, './upload/articulos/'.$archivo)) {
                $res = leer_config(dirname(__FILE__)."./src/configuracion.xml", dirname(__FILE__)."./src/configuracion.xsd");
                $bd = new PDO($res[0], $res[1], $res[2]);
                $src = './upload/articulos/'.$archivo;
                $ins = "insert into producto (nombre, descripcion, peso, stock, precio, src, codCat) values('$name', '$desc', '$peso','$stock','$precio','$src', '$codCat')";
                $resul = $bd->query($ins);

                //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
                //chmod('./upload/articulos/'.$archivo, 0777);
                //Mostramos el mensaje de que se ha subido co éxito
                echo '<div><b>Se ha subido correctamente la imagen.</b></div>';
                //Mostramos la imagen subida
                echo '<p><img src="upload/articulos/'.$archivo.'"></p>';

            }
        }

    }
}else{
    header("Location: home.php");
}

?>

<!DOCTYPE html>
<html>
<head></head>
<body>
    <h1>Añadir producto</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
    <table>
        <tr><td><label for="name">nombre: </label></td><td><input type="text" id="name" name="name" placeholder="nombre"></td><tr>
        <tr><td><label for="desc" value ="descripcion"> descripcion: </label></td><td><input type="text" id="desc" name="desc" placeholder="desc"></td><tr>
        <tr><td><label for="peso">peso: </label></td><td><input type="number" id="peso" name="peso" placeholder="peso"></td><tr>
        <tr><td><label for="stock" value ="stock"> stock: </label></td><td><input type="number" id="stock" name="stock" placeholder="stock"></td><tr>
        <tr><td><label for="precio" value ="precio"> precio: </label></td><td><input type="number" step=0.01 id="precio" name="precio" placeholder ="precio"></td><tr>
        <tr><td><label for="codCat" value ="codCat"> codCat: </label></td><td><input type="number" id="codCat" name="codCat" placeholder ="codCat"></td><tr> 
        <tr><td><label for="image" value ="Imagen">Imagen: </label></td><td><input type="file" accept=".jpg, .png, .jpeg" id="image" name="image" size="20"></td><tr>
        <tr><td><input type="submit" name="enviar" value = "Subir producto"></td><tr>
    </table>
    </form>

</body>
</html>