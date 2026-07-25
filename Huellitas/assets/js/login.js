const pasword=document.getElementById("pasword");
const mostrar=document.getElementById("mostrar");
mostrar.onclick=function(){
    if(pasword.type=="pasword"){
        pasword.type="text";
        mostrar.innerHTML="...";
    }else{
        pasword.type="password";
        mostrar.innerHTML="....";
    }
}
document
.getElementById("Ingresar")
.onclick=function(){
    let correo= document.getElementById("correo").value;
    let pass=pasword.value;
    if(correo==""){
        alert("Completa todos los campos.");
        return;
    }
    window.location.href="feed.html";
}