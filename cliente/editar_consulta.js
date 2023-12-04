
function validacion() {
    // Obtener los elementos del formulario
    var medicamentos = document.getElementById("medicamento_v");
    var cantidad = document.getElementById("cantidad_v");
    var frecuencia = document.getElementById("frecuencia_v");
    var duracion = document.getElementById("duracion_v");
    var cronica = document.getElementById("cronica_v");

    // Compruebo si se ha seleccionado un medicamento
    if (medicamentos.value == null || medicamentos.value.length == 0 || medicamentos.value.trim() == "") {
        // Cambio el borde a rojo
        medicamentos.style.borderColor = "red";
        // Añado mensaje de error
        let error = document.createElement("span");
        error.id = "vacio";
        error.textContent = "Por favor, seleccione un medicamento.";
        medicamentos.insertAdjacentElement("afterend", error);
        return false;
    } else {
        // Si vuelve a pulsar el botón y esta vez es correcto
        medicamentos.style.borderColor = "black";
        // Elimino mensaje de error
        if (document.getElementById("vacio")) {
            document.getElementById("vacio").remove();
        }
    }

    // comprobar que la cantidad y la frecuencia no estén vacíos

    if (cantidad.value.trim() == "") {
        // Cambio el borde a rojo
        cantidad.style.borderColor = "red";
        // Añado mensaje de error
        let error = document.createElement("span");
        error.id = "vacio1";
        error.textContent = "La cantidad no puede estar vacía";
        cantidad.insertAdjacentElement("afterend", error);
        return false;
    } else {
        // Si vuelve a pulsar el botón y esta vez es correcto
        cantidad.style.borderColor = "black";
        // Elimino mensaje de error
        if (document.getElementById("vacio1")) {
            document.getElementById("vacio1").remove();
        }
    }

    if (frecuencia.value.trim() == "") {
        // Cambio el borde a rojo
        frecuencia.style.borderColor = "red";
        // Añado mensaje de error
        let error = document.createElement("span");
        error.id = "vacio2";
        error.textContent = "La frecuencia no puede estar vacía";
        frecuencia.insertAdjacentElement("afterend", error);
        return false;
    } else {
        // Si vuelve a pulsar el botón y esta vez es correcto
        frecuencia.style.borderColor = "black";
        // Elimino mensaje de error
        if (document.getElementById("vacio2")) {
            document.getElementById("vacio2").remove();
        }
    }

    // Comprobar que los campos de texto tengan menos de 100 caracteres
    if (cantidad.value.length >= 100 || frecuencia.value.length >= 100 || (duracion.value.length >= 100 && !cronica.checked)) {
        // Cambio el borde a rojo
        duracion.style.borderColor = "red";
        frecuencia.style.borderColor = "red";
        cantidad.style.borderColor = "red";

        // Añado mensaje de error
        let error = document.createElement("span");
        error.id = "limite";
        error.textContent = "LOS CAMPOS DE TEXTO DEBEN TENER MENOS DE 100 CARACTERES";
        duracion.insertAdjacentElement("afterend", error);
        return false;
    } else {
        // Si vuelve a pulsar el botón y esta vez es correcto
        duracion.style.borderColor = "black";
        frecuencia.style.borderColor = "black";
        cantidad.style.borderColor = "black";
        // Elimino mensaje de error
        if (document.getElementById("limite")) {
            document.getElementById("limite").remove();
        }
    }

    // Comprobar que la duración no está vacía si no es una medicación crónica
    if (!cronica.checked && duracion.value.trim() === "") {
        // Cambio el borde a rojo
        duracion.style.borderColor = "red";
        // Añado mensaje de error
        let error = document.createElement("span");
        error.id = "vacio3";
        error.textContent = "La duracion no puede estar vacía si no es una medicación crónica";
        duracion.insertAdjacentElement("afterend", error);
        return false;
    } else {
        // Si vuelve a pulsar el botón y esta vez es correcto
        duracion.style.borderColor = "black";
        // Elimino mensaje de error
        if (document.getElementById("vacio3")) {
            document.getElementById("vacio3").remove();
        }
    }

    // Si todas las validaciones pasan, el formulario se enviará
    return true;
}
