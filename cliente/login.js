function validacion() {
    // Obtengo el valor tanto de usuario como de contraseña
    let user = document.getElementById("usuario").value;
    let pwd = document.getElementById("contrasena").value;

    // Compruebo si están vacíos
    if (user == null || user.length == 0 || user.trim() == "") {
        // Obtengo el elemento input:text de usuario
        let inputUsuario = document.getElementById("usuario");
        // Cambio el borde del input a rojo
        inputUsuario.style.borderColor = "red";
        // Añado un mensaje de error
        let error = document.createElement("span");
        error.id = "usuarioVacio";
        error.textContent = "Este campo no puede estar vacío";
        inputUsuario.insertAdjacentElement("afterend", error);
        return false;
    } else {
        // Si el usuario vuelve a pulsar el botón de enviar y esta vez hay algo, el borde vuelve a su color original
        document.getElementById("usuario").style.borderColor = "black";
        // Elimino el mensaje de error
        if (document.getElementById("usuarioVacio")) {
            document.getElementById("usuarioVacio").remove();
        }
    }

    if (pwd == null || pwd.length == 0 || pwd.trim() == "") {
        let inputPwd = document.getElementById("contrasena");
        inputPwd.style.borderColor = "red";

        let error = document.createElement("span");
        error.id = "pwdVacio";
        error.textContent = "Este campo no puede estar vacío";
        inputPwd.insertAdjacentElement("afterend", error);
        return false;
    } else {
        document.getElementById("contrasena").style.borderColor = "black";
        if (document.getElementById("pwdVacio")) {
            document.getElementById("pwdVacio").remove();
        }
    }
    return true;
}
