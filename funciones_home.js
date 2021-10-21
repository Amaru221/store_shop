function verPestania(event, css){
    let activadas = document.getElementsByClassName("active");

    for(var i = 0; i<activadas.length; i++){
        activadas[i].classList.remove('active');
    }
    let activa = event.target.id;
    document.getElementById(activa).className+=" "+css;

    let listaCajas = document.getElementsByClassName('caja');

    for(var i = 0; i<listaCajas.length; i++){
        if(listaCajas[i].classList[2] !== "visible"){
            listaCajas[i].className += " visible";
        }
    }


    switch(activa){
        case 'articulos':
            cargar_articulos();
            break;
        case 'carrito':
            cargar_carrito();
            break;
        default:
            console.log("evento de "+activa);
            break;
    }

    let cambio = document.getElementById('caja_'+activa);
    cambio.classList.remove('visible');

}

function cargar_articulos(){

    $.ajax({
        method: "GET",
        url: "cargar_articulos.php",
        data: { 
            categoria: 0
        }
    }).done(function(data) {
        console.log(data);
        let catalogo = document.createElement("div");
        catalogo.className = "catalogo";
        if(data != 0)
        {
            let arrayArticulos = JSON.parse(data);
  
            arrayArticulos.forEach( function(producto) {
                let item = document.createElement("div");
                item.className = 'catalogo-item';
                item.onclick = function(event){
                    if(event.target == this){
                        location.href = "articulo.php?codProd="+producto.codProd;
                    }else{
                        return;
                    }
                    
                }
                let img = document.createElement("img");
                img.src = producto.src;
                img.atl = producto.nombre;
                let h3 = document.createElement('h3');
                h3.innerHTML = producto.nombre;
                let p1 = document.createElement('p');
                p1.innerHTML = producto.descripcion;
                let p2 = document.createElement('p');
                p2.innerHTML = "<b>Precio:</b> "+producto.precio+" €";
                let labelUnit = document.createElement('label');
                labelUnit.innerHTML = "Cantidad: ";
                let unidades = document.createElement("input");
                unidades.id = "unit"+producto.codProd;
                unidades.type= "number";
                unidades.min = 1;
                unidades.max = producto.stock;
                unidades.value = 1;
                let button = document.createElement('button');
                button.className = "anadirButton";
                button.innerHTML = "añadir";
                button.value = "anadir";
                //button.onclick = 'anadir.php?codProd='+producto.codCat;
                button.onclick = function(event) {
                    let unidadesProd = document.getElementById('unit'+producto.codProd);
                    anadir_articulo(producto.codProd, unidadesProd.value);
                }

                item.appendChild(img);
                item.appendChild(h3);
                item.appendChild(p1);
                item.appendChild(p2);
                item.appendChild(labelUnit);
                item.appendChild(unidades);
                item.appendChild(button);
                catalogo.appendChild(item);

            });
            
        }else{
            let item = document.createElement("div");
            item.className = 'catalogo-item';
            let p1 = document.createElement("p");
            p1.innerHTML = "No hay articulos en la lista";
            item.appendChild(p1);
            catalogo.appendChild(item);

        }
        let contenedor = document.getElementById("contenedor_articulos");
        contenedor.removeChild(contenedor.firstChild);
        contenedor.appendChild(catalogo);

    });

}

function anadir_articulo(codProd, unidades){

    $.ajax({
        method: "GET",
        url: "anadir.php",
        data: { 
            codProd: codProd,
            unidades: unidades
        }
    }).done(function(data) {
        if(data == 1)
        {
            let unit = parseInt(unidades);
            let contadore = parseInt($("#contador_carrito").text());
            $("#contador_carrito").text(contadore+unit);
            //alert("Producto "+codProd+" añadido con éxito.");
            console.log("producto añadido");
        }else if(data == 2){
            alert("Revise el stock del articulo");
        
        }else if(data == 0){
            console.log("error añadiendo producto");
            //alert("Error, no se pudo añadir el producto "+codProd);
        }
    
    });

}


function cargar_carrito(){
    $.ajax({
        method: "GET",
        url: "cargar_carrito.php"
    }).done(function(data) {

        if(data != 0){
            let arrayCarrito = JSON.parse(data);
            //variable precio total articulos
            let precioTotal = 0;
            console.log(arrayCarrito);
            let cesta = document.createElement("div");
            cesta.className = "cesta";

            arrayCarrito.forEach( function(producto) {
                let item = document.createElement("div");
                item.className = 'cesta-item';
                let img = document.createElement("img");
                img.src = producto.src;
                img.atl = producto.nombre;
                let h3 = document.createElement('h3');
                h3.innerHTML = producto.nombre;
                let p1 = document.createElement('p');
                p1.innerHTML = producto.descripcion;
                let p2 = document.createElement('p');
                p2.innerHTML = producto.precio+" €";
                let labelUnit = document.createElement('p');
                labelUnit.innerHTML = "unidades: "+ producto.unidades;
                let unitProd = document.createElement('input');
                unitProd.id = "deleteUnit"+producto.codProd;
                unitProd.type= "number";
                unitProd.min = 1;
                unitProd.max = producto.unidades;
                unitProd.value = 1;
                let btnEliminar = document.createElement('button');
                btnEliminar.className = "btnEliminar";
                btnEliminar.innerHTML = "eliminar";
                btnEliminar.value = "eliminar";

                btnEliminar.onclick = function(event) {
                    let unidadesProd = document.getElementById('deleteUnit'+producto.codProd);
                    eliminar_articulo_cesta(producto.codProd, unidadesProd.value);
                }

                precioTotal += producto.precio*producto.unidades;

                item.appendChild(img);
                item.appendChild(h3);
                item.appendChild(p1);
                item.appendChild(p2);
                item.appendChild(labelUnit);
                item.appendChild(unitProd);
                item.appendChild(btnEliminar);
                cesta.appendChild(item);
                
            });
            let divCesta = document.createElement("div");
            let etotalPrecio = document.createElement("h3");
            etotalPrecio.innerHTML = "<b>Precio Total: "+precioTotal+" €</b>";
            let btnPedido = document.createElement("button");
            btnPedido.id = "btnPedido";
            btnPedido.innerHTML = "Realizar Pedido";
            btnPedido.onclick = function(event){
                realizarPedido();
            }
            let contenedorCesta = document.getElementById("contenedor_first_cesta");
            contenedorCesta.removeChild(contenedorCesta.firstChild);
            divCesta.appendChild(etotalPrecio);
            divCesta.appendChild(btnPedido);
            contenedorCesta.appendChild(divCesta);
            let contenedor = document.getElementById("contenedor_cesta");
            contenedor.removeChild(contenedor.firstChild);
            contenedor.appendChild(cesta);
           


        }else{
            console.log("cesta vacia");
            let cesta = document.createElement('div');
            cesta.className = "cesta";
            let parrafo = document.createElement('p');
            parrafo.innerHTML = "No hay articulos en la cesta";
            cesta.appendChild(parrafo);
            let contenedor = document.getElementById("contenedor_cesta");
            contenedor.removeChild(contenedor.firstChild);
            contenedor.appendChild(cesta);
            let contenedorCesta = document.getElementById("contenedor_first_cesta");
            contenedorCesta.removeChild(contenedorCesta.firstChild);
            let divCesta = document.createElement("div");
            contenedorCesta.appendChild(divCesta);

        }
    
    });
}


function eliminar_articulo_cesta($codProd, unidades){
    $.ajax({
        method: "GET",
        url: "eliminar.php",
        data: { 
            cod: $codProd,
            unidades: unidades
        }
    }).done(function(data) {
        if(data == 1){
            let unit = parseInt(unidades);
            let contadore = parseInt($("#contador_carrito").text());
            if(contadore-unit < 0){
                $("#contador_carrito").text(0);
            }else{
                $("#contador_carrito").text(contadore-unit);
            }
            
            cargar_carrito();
        }else{
            alert('No se pudo eliminar el producto');
        }
        
    });

}
function realizarPedido(){
    $.ajax({
        method: "GET",
        url: "realizar_pedido.php",
        data: {      
        }
    }).done(function(data) {
        if(data == 1){
            alert("Pedido realizado con éxito. En breves recibira un correo electrónico");
            $("#contador_carrito").text(0);
            cargar_carrito();
        }else if(data == 0){
            alert('No se pudo realizar el pedido');
        }else if(data==2){
            alert("error enviando correos");

        }
        
    });

}