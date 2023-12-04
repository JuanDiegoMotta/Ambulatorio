function validacion() {
    // Obtener la fecha ingresada por el usuario
    var fechaInput = document.getElementById("date").value;
    var fechaSeleccionada = new Date(fechaInput);

    // Obtener la fecha actual
    var fechaActual = new Date();

    // Calcular la diferencia en días entre la fecha seleccionada y la fecha actual
    var diferenciaDias = Math.floor((fechaSeleccionada - fechaActual) / (1000 * 60 * 60 * 24));

    // Compruebo si está vacío
    if (fechaInput == null || fechaInput.length == 0 || fechaInput.trim() == "") {
        // Obtengo el elemento input:date del formulario
        let inputDate = document.getElementById("date");
        // Cambio el borde a rojo
        inputDate.style.borderColor = "red";
        // Añado un mensaje de error
        let error = document.createElement("span");
        error.id = "vacio";
        error.textContent = "Por favor, íngrese una fecha.";
        inputDate.insertAdjacentElement("afterend", error);
        return false;
    } else {
        // Si vuelve a pulsar el botón y esta vez es correcto
        document.getElementById("date").style.borderColor = "black";
        // Elimino mensaje de error
        if (document.getElementById("vacio")) {
            document.getElementById("vacio").remove();
        }
    }
    // Verificar si la fecha es anterior a la fecha actual
    if (fechaSeleccionada < fechaActual) {
        // Obtengo el elemento input:date del formulario
        let inputDate = document.getElementById("date");
        // Cambio el borde a rojo
        inputDate.style.borderColor = "red";
        // Añado un mensaje de error
        let error = document.createElement("span");
        error.id = "fechaAnterior";
        error.textContent = "Fecha no válida";
        inputDate.insertAdjacentElement("afterend", error);
        return false;
    } else {
        // Si vuelve a pulsar el botón y esta vez es correcto
        document.getElementById("date").style.borderColor = "black";
        // Elimino mensaje de error
        if (document.getElementById("fechaAnterior")) {
            document.getElementById("fechaAnterior").remove();
        }
    }

    // Verificar si la fecha es un fin de semana (sábado o domingo)
    var diaSemana = fechaSeleccionada.getDay();
    if (diaSemana === 0 || diaSemana === 6) {
        // Obtengo el elemento input:date del formulario
        let inputDate = document.getElementById("date");
        // Cambio el borde a rojo
        inputDate.style.borderColor = "red";
        // Añado un mensaje de error
        let error = document.createElement("span");
        error.id = "fechaFinde";
        error.textContent = "Elija un día laborable";
        inputDate.insertAdjacentElement("afterend", error);
        return false;
    } else {
        // Si vuelve a pulsar el botón y esta vez es correcto
        document.getElementById("date").style.borderColor = "black";
        // Elimino mensaje de error
        if (document.getElementById("fechaFinde")) {
            document.getElementById("fechaFinde").remove();
        }
    }

    // Verificar si la fecha está a más de 30 días en el futuro
    if (diferenciaDias > 30) {
        // Obtengo el elemento input:date del formulario
        let inputDate = document.getElementById("date");
        // Cambio el borde a rojo
        inputDate.style.borderColor = "red";
        // Añado un mensaje de error
        let error = document.createElement("span");
        error.id = "masde30";
        error.textContent = "Selecciona una fecha que se encuentre entre los próximos 30 días";
        inputDate.insertAdjacentElement("afterend", error);
        return false;
    } else {
        // Si vuelve a pulsar el botón y esta vez es correcto
        document.getElementById("date").style.borderColor = "black";
        // Elimino mensaje de error
        if (document.getElementById("masde30")) {
            document.getElementById("masde30").remove();
        }
    }

    // Si todas las validaciones pasan, el formulario se enviará
    return true;
}


