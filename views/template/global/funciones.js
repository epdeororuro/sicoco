//******* funciones genericas ***
function AccionAjax(ruta, datos, callbackExito, mensajeExito) {
    $.ajax({
        method: datos ? "POST" : "GET",
        url: ruta,
        data: datos,
        success: function(e) {
            try {
                // Intentamos leer la respuesta como JSON estructurado
                var response = typeof e === 'object' ? e : JSON.parse($.trim(e));
                if (response.status === 'success') {
                    if (typeof callbackExito === "function") callbackExito();
                    Swal.fire(response.message || mensajeExito, 'Presione Ok para continuar', 'success');
                } else {
                    Swal.fire(response.message || 'Error en la operación', 'Presione Ok para continuar', 'error');
                }
            } catch (err) {
                // Fallback de retrocompatibilidad: para controladores que aún devuelven "1" o texto plano
                if ($.trim(e) == "1") {
                    if (typeof callbackExito === "function") callbackExito();
                    Swal.fire(mensajeExito, 'Presione Ok para continuar', 'success');
                } else {
                    Swal.fire(e, 'Presione Ok para continuar', 'error');
                }
            }
        },
        error: function() {
            Swal.fire('Error', 'Problema de conexión con el servidor', 'error');
        }
    });
}