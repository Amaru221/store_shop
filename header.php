<header>
    <nav id ="navegador" class = "nav">
        <a id="novedades" class="nav-link active" href="#" onclick="verPestania(event, 'active' );">Novedades</a>
        <a id="promociones" class="nav-link" href="#" onclick="verPestania(event, 'active' );">Promociones</a>
        <a id="articulos" class="nav-link" href="#" onclick="verPestania(event, 'active' );">Articulos</a>
        <a id="carrito" class="nav-link" href="#" onclick="verPestania(event, 'active' );">Mi Cesta <span id="contador_carrito" class = "contador_carrito"><?= count($_SESSION['carrito'])?></span></a>
        <a class="nav-link" href="logout.php">Usuario: <?= $_SESSION['usuario']['usuario'] ?> Cerrar sesión</a>
        
    </nav>
</header>
<hr>