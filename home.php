<?php

require_once "sesiones.php";
comprobar_sesion();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="./src/home.css">
        <script type="text/javascript" src="funciones_home.js"></script>
    </head>
    
    <body>
    <?php require "header.php"?>
        <div id="caja_novedades" class="caja_novedades caja">
            <h2 class = "title_caja">Novedades</h2>
        </div>
        <div id="caja_promociones" class="caja_promociones caja visible">
            <h2 class = "title_caja">Promociones</h2>
        </div>
        <div id="caja_articulos" class="caja_articulos caja visible">
            <h2 class = "title_caja" >Articulos</h2>
            <br>
            <div id="contenedor_articulos">
            </div>
        </div>
        <div id="caja_carrito" class="caja_carrito caja visible">
            <h2 class = "title_caja">Mi cesta</h2>
            <div id = "contenedor_first_cesta">
            </div>
            <br>  
            <div id="contenedor_cesta">
            </div>
        </div>

    <?php require "footer.php" ?>
    </body>
</html>