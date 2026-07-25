document
.getElementById("crear")
.onclick=function(){
    let nombre=document.getElementById("nombre").value;
    let correo=document.getElementById("correo").value;
    let password=document.getElementById("password").value;
    let confirmar=document.getElementById("confirmar").value;
    let tipo=document.querySelector('input[name="tipo"]:checked');
    if(nombre=="" || correo=="" || password=="" || confirmar==""){
        alert("Completa todos los campos.");
        return;
}
if(password!=confirmar){
    alert("Las contraseñas no coinciden.");
    return;
}

if(tipo==null){
    alert("Selecciona un tipo de usuario.");
    return;
}

alert("Cuenta creada correctamente.");
window.location.href="login.html";
}