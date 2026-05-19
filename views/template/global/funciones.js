//******* funciones genericas ***
function AccionAjax(ruta, datos, callbackExito, mensajeExito) {
    $.ajax({
        method: datos ? "POST" : "GET",
        url: ruta,
        data: datos,
        success: function(e) {
            if ($.trim(e) == "1") {
                if (typeof callbackExito === "function") callbackExito();
                Swal.fire(mensajeExito, 'Presione Ok para continuar', 'success');
            } else {
                Swal.fire(e, 'Presione Ok para continuar', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Problema de conexión con el servidor', 'error');
        }
    });
}