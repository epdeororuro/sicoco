//******* funciones de login***
function Login(){
  const ruta=base_url+'login/usr';
var datos= $("#FormLogin").serialize();

$.ajax({
    method:"post",
    url:ruta,
    data:datos,
    success: function(e){  
     var respuesta = $.trim(e);
     if (respuesta=="0"){

Swal.fire({
  title: "Acceso Denegado",
  text: "Usuario o contraseña incorrectos",
  icon: "error",
  confirmButtonText: "Reintentar",
  confirmButtonColor: "#900C3F"
});

     }
     else if(respuesta.startsWith("2-")){
        // Si la respuesta empieza con 2, requiere OTP
        var cadena = respuesta.split("-");
        var id_usuario = cadena[1];
        var celular = cadena[2];
        var codigo_otp = cadena[3];
        
        // Armar y abrir link gratuito de WhatsApp
        var texto_wa = "🔒 *EPDEOR - Seguridad*%0A%0ASu código de acceso al sistema es: *" + codigo_otp + "*%0A%0A_Este código expirará en 5 minutos._";
        var url_wa = "https://api.whatsapp.com/send?phone=" + celular + "&text=" + texto_wa;
        window.open(url_wa, '_blank');
        
        $("#id_usuario_tmp").val(id_usuario);
        $("#FormLogin").hide();
        $("#FormOTP").fadeIn("slow");
        $("#txt_otp").focus();
     }
     else
      { 
        Swal.fire('Error', 'Respuesta inesperada del servidor: ' + respuesta, 'error');
      }
     
    }
  });
}

function ValidarOTP() {
    const ruta = base_url + 'login/validar_otp';
    var datos = $("#FormOTP").serialize();

    $.ajax({
        method: "post",
        url: ruta,
        data: datos,
        success: function(e) {
            var respuesta = $.trim(e);
            if (respuesta.startsWith("1-")) {
                var cadena = respuesta.split("-");
                Swal.fire({
                    title: "¡Verificación Exitosa!",
                    icon: "success",
                    html: "Operador: <b>" + cadena[1] + "</b><br>Ingresando al sistema...",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    allowOutsideClick: false
                }).then(() => {
                    window.location.replace(base_url + "inicio");
                });
            } else {
                Swal.fire('Error de Seguridad', e, 'warning');
                $("#txt_otp").val('').focus();
            }
        }
    });
}

function ReenviarOTP() {
    var id_usuario = $("#id_usuario_tmp").val();
    if(!id_usuario) return;
    
    $.ajax({
        method: "post",
        url: base_url + 'login/reenviar_otp',
        data: { id_usuario_tmp: id_usuario },
        success: function(e) {
            var respuesta = $.trim(e);
            if (respuesta.startsWith("1-")) {
                var cadena = respuesta.split("-");
                var celular = cadena[1];
                var codigo_otp = cadena[2];
                
                var texto_wa = "🔒 *EPDEOR - Seguridad*%0A%0ASu NUEVO código de acceso al sistema es: *" + codigo_otp + "*%0A%0A_Este código expirará en 5 minutos._";
                var url_wa = "https://api.whatsapp.com/send?phone=" + celular + "&text=" + texto_wa;
                window.open(url_wa, '_blank');
                
                Swal.fire({
                    icon: 'info',
                    title: 'Código Reenviado',
                    text: 'Se generó un nuevo código y se abrió WhatsApp.',
                    timer: 3000,
                    showConfirmButton: false
                });
                $("#txt_otp").val('').focus();
            } else {
                Swal.fire('Error', 'La sesión expiró. Vuelva a Iniciar Sesión.', 'error');
                CancelarOTP();
            }
        }
    });
}

function CancelarOTP() {
    $("#FormOTP").hide();
    $("#txt_otp").val('');
    $("#FormLogin").fadeIn("slow");
    document.getElementsByName("txt_clave")[0].value = "";
    document.getElementsByName("txt_clave")[0].focus();
}