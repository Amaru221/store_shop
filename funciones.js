function verPestania(event, css){
    let activas = document.getElementsByClassName("active");

    for(var i = 0; i<activas.length; i++){
        activas[i].classList.remove("active");
    }

    let activada = event.target.id;
    document.getElementById(activada).className +=" "+css;

    let listaCajas = document.getElementsByClassName("caja");

    for(var i = 0; i<listaCajas.length; i++){
        if(listaCajas[i].classList[2] !== "visible"){
            listaCajas[i].className += " visible";
        }
    }

    let cambio = document.getElementById("caja_"+activada);
    //console.log(document.getElementById("caja_"+activada));
	cambio.classList.remove("visible");
}


function comprobar_sesion(){
    let correo = document.getElementById("correo").value;
    let clave = document.getElementById("clave").value;

    $.ajax({
        method: "GET",
        url: "comprobar_sesion.php",
        data: { 
            correo: correo,
            clave: clave
        }
    }).done(function(data) {
        console.log(data);
        if(data == 1){
            location.href = "home.php";
        }else{
            alert("Error de autenticación. Comprueba el correo y contraseña.");
        }
        
    });
}


function registrar_user(){
    $.ajax({
        method: "POST",
        url: "registro.php",
        
        data : $("#formulario-registro").serialize()
    }).done(function(data) {

        console.log(data);
        if(data == 1){
            alert("Registro con exito. Ya puede iniciar sesión.");
            location.href = "login.html";
        }else if(data == 2){
            alert("Error en sentencia insert");
        }else if(data == 0){
            alert("ya existe este usuario");      
        }else{
            alert("Error de autenticación. Comprueba el correo y contraseña.");
        }
    });

    /*data: {
            user: usuario,
            correo: correo,
            clave: clave
        }*/

    /*let usuario = document.getElementById("user").value;
    let correo = document.getElementById("correo1").value;
    let clave = document.getElementById("clave1").value;    
    $.post("registro.php",
        {
            user: usuario,
            correo: correo,
            clave: clave
        }).done(function(data){
        console.log(data);
        if(data == 1){
            alert("Registro con exito. Ya puede iniciar sesión.");
            location.href = "login.html";
        }else if(data == 2){
            alert("Error en sentencia insert");
        }else if(data == 0){
            alert("ya existe este usuario");      
        }else{
            alert("Error de autenticación. Comprueba el correo y contraseña.");
        }
    });*/

}

