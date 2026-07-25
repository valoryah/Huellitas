const botones = document.querySelectorAll("button");

botones.forEach((boton)=>{
    boton.onclick = function(){
        alert("Aquí se mostrará toda la información de la solicitud.");
    }
});