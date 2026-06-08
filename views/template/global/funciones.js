//******* funciones genericas ***
function AccionAjax(ruta, datos, callbackExito, mensajeExito) {
    $.ajax({
        method: datos ? "POST" : "GET",
        url: ruta,
        data: datos,
        success: function(e) {
            var respStr = $.trim(e);
            
            // Retrocompatibilidad: Si la respuesta es un simple "1" de éxito de PHP
            if (respStr === "1") {
                if (typeof callbackExito === "function") callbackExito();
                Swal.fire(mensajeExito, 'Presione Ok para continuar', 'success');
                return;
            }

            try {
                // Intentamos leer la respuesta como JSON estructurado
                var response = typeof e === 'object' ? e : JSON.parse(respStr);
                if (response.status === 'success') {
                    if (typeof callbackExito === "function") callbackExito();
                    Swal.fire(response.message || mensajeExito, 'Presione Ok para continuar', 'success');
                } else {
                    Swal.fire(response.message || 'Error en la operación', 'Presione Ok para continuar', 'error');
                }
            } catch (err) {
                // Fallback de retrocompatibilidad: Si es texto pero no "1" (Mensajes de error del backend)
                Swal.fire(respStr || 'Error en la operación', 'Presione Ok para continuar', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Problema de conexión con el servidor', 'error');
        }
    });
}