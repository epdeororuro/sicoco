//********* Contrato************
function CrudContrato(operacion)
{ const ruta=base_url+operacion;
  var op=operacion.split('/');
  var datos=$("#FormContrato").serialize();
  var msj;
  switch (op[1])
  {
    case 'add':  msj='Registro Insertado con Éxito'; break;
    case 'edit': msj='Registro Modificado con Éxito'; break;
    case 'delete': msj='Registro Eliminado con Éxito'; datos=null; break;
    case 'confirmar': msj='Contrato Confirmado con Éxito'; datos=null; break;    
  }
  AccionAjax(ruta, datos, ListarContrato, msj);
}// fin CrudContrato

function LimpiarCamposContrato()
{
  // Limpiar Selects de Área y Catálogo visualmente
  $('#SelArea').val('0').trigger('change.select2');
  $('#SelItemCatalogo').empty().append('<option value="0">-- Primero seleccione un Área --</option>').prop('disabled', true).trigger('change.select2');

  // Limpiar inputs del contrato
  $("#txt_alquiler_ref").val('');
  document.getElementsByName("txt_actividad")[0].value = "";
  document.getElementsByName("txt_razon_social")[0].value = "Sin dato";
  document.getElementsByName("txt_contrato")[0].value = "";
  document.getElementsByName("txt_fecha_suscripcion")[0].value = "";
  document.getElementsByName("txt_fecha_inicio")[0].value = "";
  $("#txt_fecha_inicio").removeAttr("min");
  $("#btn_recomendacion_inicio").hide();
  document.getElementsByName("txt_tiempo")[0].value = "1";
  $("#texto_duracion").text("Seleccione la Fecha de Inicio para calcular el tiempo del contrato.");

  // Limpiar inputs del cliente
  $("#txt_cedula, #txt_nombres, #txt_paterno, #txt_materno").val('');
  $("#txt_celular, #txt_direccion").val(''); // #txt_latitud, #txt_longitud removidos temporalmente
} // fin LimpiarCamposContrato()


function LlenarCliente()
{ 
  const ruta=base_url+'contrato/listar_clientes';  
  $.ajax({
    url:ruta,
    type:'POST',
    dataType: 'json',
    success: function(e){
        var datos = e.data ? e.data : e; // Adaptación para nuevo JSON
        $(datos).each(function(i, v){ // indice, valor
            $("#SelBuscarCliente").append('<option value="' + v.IDCLIENTE + '">' + v.BCLIENTE + '</option>');
        });
     }
});  
} // final funcion llenar cliente combobox

function LlenarArea()
{ 
  const ruta=base_url+'contrato/listar_areas';  
  $.ajax({
    url:ruta,
    type:'POST',
    dataType: 'json',
    success: function(e){
        var datos = e.data ? e.data : e; 
        $("#SelArea").empty().append('<option value="0">-- Seleccione un Área --</option>');
        $(datos).each(function(i, v){ 
            $("#SelArea").append('<option value="' + v.IDAREA + '">' + v.DISTRIBUCION + '</option>');
        });
        // IMPORTANTE: Refrescar Select2 de forma segura
        $("#SelArea").trigger('change.select2');
     }
});  
} // final funcion llenar area

function LlenarCatalogo()
{ 
  const ruta=base_url+'contrato/listar_catalogo';  
  $.ajax({
    url:ruta,
    type:'POST',
    dataType: 'json',
    success: function(e){
        var datos = e.data ? e.data : e; // Adaptación para nuevo JSON
        $(datos).each(function(i, v){ // indice, valor
            var option = '<option value="' + v.IDCATALOGO + '">' + v.BESPACIO + '</option>';
            $("#SelBuscarCatalogo").append(option); // Removido SelItemCatalogo para no interferir con el filtro por área
        });
     }
});  
} // final funcion Llenar_servicios combobox

function ListarDetalle(idarriendo){
  const ruta = base_url + 'pagos/plan_pagos/' + idarriendo;
  
  $("#TablaDetalle").DataTable({     
    "responsive":true,
   "destroy":true, 
   "bPaginate": false, // Desactiva paginación aquí para que todos los checkbox se detecten a la vez
   "order": [],
   "autoWidth": false,
      "ajax":{
        "url": ruta,
        "dataSrc": function(json) { 
            var datos = json.data ? json.data : json; 
            if(datos && datos.length > 0) {
                $("#mensaje_sin_pagos").hide();
                $("#contenedor_tabla_pagos").show();
                $('#btn_pagar_seleccionados').prop('disabled', true).html('<i class="fas fa-dollar-sign"></i> Pagar Seleccionados');
            } else {
                $("#contenedor_tabla_pagos").hide();
                $("#mensaje_sin_pagos").show();
            }
            return datos; 
        }       
      },
      "createdRow": function(row, data, dataIndex) {
          if (data.PENDIENTE === 'SI') {
              var hoy = new Date();
              var partes = data.PERIODO.split('-');
              if (partes.length === 2 && (parseInt(partes[0]) < hoy.getFullYear() || (parseInt(partes[0]) === hoy.getFullYear() && parseInt(partes[1]) < (hoy.getMonth() + 1)))) {
                  // Pinta toda la fila de rojo si el mes está vencido
                  $(row).addClass('table-danger text-danger');
              }
          }
      },
      "columns":[
      {"data": null, "orderable": false, "searchable": false, "className": "text-center", "render": function(data, type, row) {
          if(row.PENDIENTE === 'SI') {
              return "<input type='checkbox' class='chk_pago' style='transform: scale(1.5); cursor: pointer;' data-id='"+row.IDPAGO+"' data-monto='"+row.MONTO+"' data-periodo='"+row.PERIODO+"'>";
          }
          return "<i class='fas fa-check text-success'></i>";
      }},
      {"data": "PERIODO", "render": function(data) {
          var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
          if(data && data.includes('-')) {
              var mesIndex = parseInt(data.split('-')[1]) - 1;
              return meses[mesIndex];
          }
          return data;
      }},
      {"data": "PERIODO"},
      {"data": "MONTO"},
      {"data": "PENDIENTE", "render": function(data, type, row) {
          if (data === 'SI') {
              var hoy = new Date();
              var partes = row.PERIODO.split('-');
              if (partes.length === 2 && (parseInt(partes[0]) < hoy.getFullYear() || (parseInt(partes[0]) === hoy.getFullYear() && parseInt(partes[1]) < (hoy.getMonth() + 1)))) {
                  return '<span class="badge badge-danger"><i class="fas fa-exclamation-circle"></i> VENCIDO</span>';
              }
              return '<span class="badge badge-warning"><i class="fas fa-clock"></i> PENDIENTE</span>';
          }
          return '<span class="badge badge-success"><i class="fas fa-check-double"></i> PAGADO</span>';
      }},
      {"data": null, "render": function(data, type, row) {
          if(row.PENDIENTE === 'SI') {
              var hoy = new Date();
              var partes = row.PERIODO.split('-');
              if (partes.length === 2 && (parseInt(partes[0]) < hoy.getFullYear() || (parseInt(partes[0]) === hoy.getFullYear() && parseInt(partes[1]) < (hoy.getMonth() + 1)))) {
                  return "<span class='text-danger font-weight-bold'><i class='fas fa-exclamation-triangle'></i> Mora</span>";
              }
              return "<span class='text-muted'><i class='fas fa-clock'></i> Por cobrar</span>";
          }
          return "<span class='text-success'><i class='fas fa-check'></i> Completado</span> <a href='"+base_url+"pagos/imprimir_recibo_multiple?ids="+row.IDPAGO+"' target='_blank' class='btn btn-danger btn-sm ml-2' title='Imprimir Recibo PDF'><i class='fas fa-file-pdf'></i></a>";
      }}
      ]
    }); 
} // fin de funcion listarContrato

// =========================================================================
// MANEJO DEL MAPA DE GOOGLE MAPS PARA UBICACIÓN DEL CLIENTE
// =========================================================================
/* -- MAPA DESHABILITADO TEMPORALMENTE --
var mapaCliente;
var marcadorCliente;

function inicializarMapa() {
    // Coordenadas por defecto (Ej: Ciudad de Oruro, Bolivia)
    var posicionInicial = { lat: -17.9647, lng: -67.1152 };
    
    if (!mapaCliente) {
        mapaCliente = new google.maps.Map(document.getElementById('mapa_ubicacion'), {
            center: posicionInicial,
            zoom: 14
        });

        marcadorCliente = new google.maps.Marker({
            position: posicionInicial,
            map: mapaCliente,
            draggable: true,
            title: "Ubicación del Arrendatario"
        });

        // Evento al mover el marcador manualmente
        google.maps.event.addListener(marcadorCliente, 'dragend', function() {
            $('#txt_latitud').val(marcadorCliente.getPosition().lat().toFixed(8));
            $('#txt_longitud').val(marcadorCliente.getPosition().lng().toFixed(8));
        });

        // Evento al hacer clic en cualquier parte del mapa
        google.maps.event.addListener(mapaCliente, 'click', function(event) {
            marcadorCliente.setPosition(event.latLng);
            $('#txt_latitud').val(event.latLng.lat().toFixed(8));
            $('#txt_longitud').val(event.latLng.lng().toFixed(8));
        });
    } else {
        // Si el mapa ya existe, forzamos un redibujado porque estaba oculto
        google.maps.event.trigger(mapaCliente, 'resize');
    }
}

function colocarMarcador(lat, lng) {
    if (mapaCliente && marcadorCliente && lat && lng) {
        var pos = { lat: parseFloat(lat), lng: parseFloat(lng) };
        marcadorCliente.setPosition(pos);
        mapaCliente.setCenter(pos);
        mapaCliente.setZoom(16);
    }
}
*/

// =========================================================================
// EVENTOS DEL DOM MOVIDOS DESDE LA VISTA (index.php) PARA MANTENER MVC LIMPIO
// =========================================================================

$(document).ready(function(){
  // Desactivar el enforceFocus de Bootstrap para que SweetAlert2 permita escribir en sus inputs
  $.fn.modal.Constructor.prototype._enforceFocus = function() {};

  // Inicializar mapa solo cuando el Modal termine de abrirse
  // $('#ModalContrato').on('shown.bs.modal', function () {
  //     inicializarMapa();
  // });

  // Inicializar Select2 corrigiendo el bloqueo del filtrador en Modales de Bootstrap
  $('#SelArea, #SelItemCatalogo').select2({
      dropdownParent: $('#ModalContrato')
  });
  
  // Ejecutar carga inicial de áreas
  LlenarArea();

  // Inicializar Datatable de Contratos al cargar la página
  ListarContrato();
  // $("#SelBuscarCliente").select2(LlenarCliente()); // <- Removido en la nueva versión visual

  $("#btn_InsertContrato").on('click', function(e){
    e.preventDefault();
    CrudContrato('contrato/add');
  });

  $("#btn_EditarContrato").on('click', function(e){
    e.preventDefault();
    CrudContrato('contrato/edit');
  });

  $("#btnNuevoRegistro").on('click', function(e){
    e.preventDefault();
    $("#titulo").html("Registro de Contratos");
    $("#OpcionEditar").hide();
    $("#OpcionNuevo").show("slow");
    LimpiarCamposContrato();
  });

  // Evento Cierre de Gestión Anual
  $("#btnCierreGestion").on('click', function(e) {
      e.preventDefault();
      Swal.fire({
          title: '¿Confirmar CIERRE DE GESTIÓN?',
          html: '<span class="text-danger font-weight-bold">¡ADVERTENCIA IRREVERSIBLE!</span><br><br>Esta acción finalizará <b>TODOS</b> los contratos vigentes (Pasarán a estado histórico) y <b>LIBERARÁ</b> todas las tiendas y espacios en el Catálogo para la nueva gestión.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: '<i class="fas fa-calendar-times"></i> Sí, Ejecutar Cierre Anual',
          cancelButtonText: 'Cancelar'
      }).then((result) => {
          if (result.isConfirmed) {
              Swal.fire({ title: 'Procesando...', text: 'Finalizando contratos y liberando catálogo.', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
              $.ajax({
                  url: base_url + 'contrato/cierre_gestion',
                  type: 'POST',
                  dataType: 'json',
                  success: function(resp) {
                      if(resp.status === 'success') {
                          Swal.fire('¡Cierre Exitoso!', resp.message, 'success');
                          $('#TablaContrato').DataTable().ajax.reload();
                      } else {
                          Swal.fire('Error', resp.message, 'error');
                      }
                  },
                  error: function() {
                      Swal.fire('Error', 'Problema de conexión al servidor.', 'error');
                  }
              });
          }
      });
  });

  // Lógica Correlativa de Selección Múltiple
  $(document).on('change', '.chk_pago', function() {
      var $chkboxes = $('.chk_pago');
      var idx = $chkboxes.index(this);
      var isChecked = $(this).is(':checked');

      if (isChecked) {
          // Si se marca uno, se marcan TODOS los anteriores
          $chkboxes.each(function(i) {
              if (i <= idx) $(this).prop('checked', true);
          });
      } else {
          // Si se desmarca uno, se desmarcan TODOS los posteriores
          $chkboxes.each(function(i) {
              if (i >= idx) $(this).prop('checked', false);
          });
      }
      
      var total = 0;
      var count = 0;
      $chkboxes.filter(':checked').each(function() {
          total += parseFloat($(this).data('monto'));
          count++;
      });
      
      if (count > 0) {
          $('#btn_pagar_seleccionados').prop('disabled', false).html('<i class="fas fa-dollar-sign"></i> Pagar ' + count + ' Mes(es) (Bs. ' + total.toFixed(2) + ')');
      } else {
          $('#btn_pagar_seleccionados').prop('disabled', true).html('<i class="fas fa-dollar-sign"></i> Pagar Seleccionados');
      }
  });

  // Enviar Pagos en Bloque
  $('#btn_pagar_seleccionados').on('click', function(e) {
      e.preventDefault();
      var $checked = $('.chk_pago:checked');
      if ($checked.length === 0) return;

      var ids = [];
      var total = 0;
      var periodos = [];
      $checked.each(function() {
          ids.push($(this).data('id'));
          total += parseFloat($(this).data('monto'));
          periodos.push($(this).data('periodo'));
      });
      
      var idpagos_str = ids.join(',');
      var texto_meses = periodos.length > 1 ? periodos[0] + ' al ' + periodos[periodos.length - 1] : periodos[0];

      // --- Nuevo Modal de Swal con Formulario ---
      $('#ModalDetalle').removeAttr('tabindex'); // Corrección para permitir foco en inputs de SweetAlert2
      Swal.fire({
          title: 'Confirmar Cobro y Facturación',
          icon: 'question',
          html: `
              <p class="mb-3">Se registrará el cobro de <strong>${periodos.length} mes(es)</strong> (${texto_meses}) por un total de <strong>Bs. ${total.toFixed(2)}</strong>.</p>
              <hr>
              <div class="text-left">
                  <div class="form-group">
                      <label for="swal_metodo_pago"><strong>Método de Pago:</strong></label>
                      <select id="swal_metodo_pago" class="form-control">
                          <option value="EFECTIVO" selected>Efectivo</option>
                          <option value="TRANSFERENCIA">Transferencia Bancaria</option>
                          <option value="DEPOSITO">Depósito Bancario</option>
                          <option value="QR">Pago por QR</option>
                      </select>
                  </div>
                  <div class="form-group" id="swal_div_comprobante" style="display:none;">
                      <label for="swal_nro_comprobante"><strong>Nro. de Transacción/Comprobante:</strong></label>
                      <input type="text" id="swal_nro_comprobante" class="form-control" placeholder="Ej: 845123">
                  </div>
                  <div class="form-group">
                      <label for="swal_nro_factura"><strong>Nro. de Factura SIAT (Opcional):</strong></label>
                      <input type="text" id="swal_nro_factura" class="form-control" placeholder="Ej: 10258">
                  </div>
              </div>
          `,
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Sí, Cobrar y Registrar',
          didOpen: () => {
              $('#swal_metodo_pago').on('change', function() {
                  if ($(this).val() === 'EFECTIVO') {
                      $('#swal_div_comprobante').hide();
                  } else {
                      $('#swal_div_comprobante').show();
                  }
              });
          },
          preConfirm: () => {
              return {
                  metodo_pago: $('#swal_metodo_pago').val(),
                  nro_comprobante: $('#swal_nro_comprobante').val(),
                  nro_factura_siat: $('#swal_nro_factura').val()
              }
          }
      }).then((result) => {
          if (result.isConfirmed && result.value) {
              var postData = result.value;
              postData.idpagos = idpagos_str;
              $.ajax({
                  url: base_url + 'pagos/realizar_pago_multiple',
                  type: 'POST',
                  data: postData,
                  dataType: 'json',
                  success: function(resp) {
                      if(resp.status === 'success') {
                          $('#TablaDetalle').DataTable().ajax.reload();
                          $('#btn_pagar_seleccionados').prop('disabled', true).html('<i class="fas fa-dollar-sign"></i> Pagar Seleccionados');
                          Swal.fire({
                              title: '¡Cobro Registrado!',
                              text: 'Los pagos se agruparon y guardaron exitosamente.',
                              icon: 'success',
                              showCancelButton: true,
                              confirmButtonColor: '#28a745',
                              cancelButtonColor: '#6c757d',
                              confirmButtonText: '<i class="fas fa-print"></i> Imprimir Recibo',
                              cancelButtonText: 'Cerrar'
                          }).then((res) => {
                              if (res.isConfirmed) {
                                  window.open(base_url + 'pagos/imprimir_recibo_multiple?ids=' + idpagos_str, '_blank');
                              }
                          });
                      } else {
                          Swal.fire('Error', resp.message, 'error');
                      }
                  }
              });
          }
      });
  });

  // -------------------------------------------------------------------------
  // EVENTO EN CADENA: Cuando el usuario cambia el Área
  // -------------------------------------------------------------------------
  $("#SelArea").on("change", function() {
    var idarea = $(this).val();
    var $selectCatalogo = $("#SelItemCatalogo");
    
    $selectCatalogo.empty().append('<option value="0">-- Seleccione un Espacio / Servicio --</option>');
    $("#txt_alquiler_ref").val(''); // Limpia el precio al cambiar área
    
    if (idarea && idarea != "0") {
        $selectCatalogo.prop('disabled', false); // Habilita el select
        
        $.ajax({
            url: base_url + 'contrato/listar_catalogo_por_area/' + idarea,
            type: 'POST',
            dataType: 'json',
            success: function(e) {
                var datos = e.data ? e.data : e;
                $(datos).each(function(i, v) {
                    var option = '<option value="' + v.IDCATALOGO + '" data-precio="' + v.ALQUILER + '">' + v.BESPACIO + '</option>';
                    $selectCatalogo.append(option);
                });
                $selectCatalogo.trigger('change.select2'); // Refresca Select2 visualmente
            }
        });
    } else {
        $selectCatalogo.prop('disabled', true);
            $selectCatalogo.trigger('change.select2');
    }
  });
  
  // Escuchar cuando el usuario selecciona un ítem del catálogo para mostrar el precio
  $("#SelItemCatalogo").on("change", function() {
      var precio = $(this).find(':selected').data('precio');
      $("#txt_alquiler_ref").val(precio ? precio : '');
  });

  // Lógica para asignar "Hoy" a Fecha de Suscripción
  $(document).on('click', '#btn_hoy_suscripcion', function(e) {
      e.preventDefault();
      var hoy = new Date();
      var dia = ("0" + hoy.getDate()).slice(-2);
      var mes = ("0" + (hoy.getMonth() + 1)).slice(-2);
      var fechaFormateada = hoy.getFullYear() + "-" + mes + "-" + dia;
      $("#txt_fecha_suscripcion").val(fechaFormateada).trigger('change');
  });

  // Restringir que Fecha de Inicio no sea menor a Fecha de Suscripción
  $("#txt_fecha_suscripcion").on('change', function() {
      var fechaSuscripcion = $(this).val();
      var $inputInicio = $("#txt_fecha_inicio");
      if(fechaSuscripcion) {
          $inputInicio.attr('min', fechaSuscripcion);
          
          // Mostrar recomendación dinámica
          var partes = fechaSuscripcion.split('-');
          var fechaFormat = partes[2] + "/" + partes[1] + "/" + partes[0];
          $("#lbl_fecha_recomendada").text(fechaFormat);
          $("#btn_recomendacion_inicio").show();
          
          if($inputInicio.val() && $inputInicio.val() < fechaSuscripcion) {
              $inputInicio.val(fechaSuscripcion).trigger('change');
          }
      } else {
          $inputInicio.removeAttr('min');
          $("#btn_recomendacion_inicio").hide();
      }
  });

  // Acción al hacer clic en la recomendación de Fecha de Inicio
  $(document).on('click', '#btn_recomendacion_inicio', function(e) {
      e.preventDefault();
      var fechaSuscripcion = $("#txt_fecha_suscripcion").val();
      if(fechaSuscripcion) {
          $("#txt_fecha_inicio").val(fechaSuscripcion).trigger('change');
      }
  });

  // Auto-calcular meses y días hasta final de año al cambiar Fecha Inicio
  $("#txt_fecha_inicio").on('change', function(){
      var fecha = $(this).val();
      if(fecha){
          var partes = fecha.split('-');
          var anio = parseInt(partes[0]);
          var mes = parseInt(partes[1]);
          var dia = parseInt(partes[2]);
          
          var diasEnMes = new Date(anio, mes, 0).getDate();
          var diasRestantes = diasEnMes - dia + 1;
          var mesesRestantes = 12 - mes;
          
          if (diasRestantes === diasEnMes) {
              mesesRestantes += 1;
              diasRestantes = 0;
          }
          
          var textoMeses = mesesRestantes > 0 ? mesesRestantes + (mesesRestantes === 1 ? " mes" : " meses") : "";
          var textoDias = diasRestantes > 0 ? diasRestantes + (diasRestantes === 1 ? " día" : " días") : "";
          var union = (mesesRestantes > 0 && diasRestantes > 0) ? " y " : "";
          
          $("#texto_duracion").text("Se está estableciendo un contrato por " + textoMeses + union + textoDias + " hasta fin de año.");
          $("#txt_tiempo").val(mesesRestantes + (diasRestantes > 0 ? 1 : 0));
      } else {
          $("#txt_tiempo").val(1);
          $("#texto_duracion").text("Seleccione la Fecha de Inicio para calcular el tiempo del contrato.");
      }
  });

  // Autocompletar datos del cliente al ingresar la Cédula (CI)
  $("#txt_cedula").on('blur', function() {
    var ci = $(this).val().trim();
    
    if (ci.length > 0) {
        $(this).addClass('is-warning');

        $.ajax({
            url: base_url + 'cliente/buscar_por_ci',
            type: 'POST',
            data: { cedula: ci },
            dataType: 'json',
            success: function(response) {
                $("#txt_cedula").removeClass('is-warning');
                
                if (response.status === 'success' && response.data) {
                    $("#txt_nombres").val(response.data.NOMBRES);
                    $("#txt_paterno").val(response.data.PATERNO);
                    $("#txt_materno").val(response.data.MATERNO);
                    $("#txt_celular").val(response.data.CONTACTOS);
                    $("#txt_direccion").val(response.data.DIRECCION);
                    // $("#txt_latitud").val(response.data.LATITUD);
                    // $("#txt_longitud").val(response.data.LONGITUD);
                    
                    // Centrar el marcador en el mapa si este cliente ya tiene coordenadas guardadas
                    // colocarMarcador(response.data.LATITUD, response.data.LONGITUD);
                    
                    Swal.fire({
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                        icon: 'success', title: 'Cliente encontrado. Datos cargados.'
                    });
                } else {
                    $("#txt_nombres, #txt_paterno, #txt_materno, #txt_celular, #txt_direccion").val('');
                    // $("#txt_latitud, #txt_longitud").val('');
                }
            },
            error: function() {
                $("#txt_cedula").removeClass('is-warning');
                console.error("Error de conexión al buscar el cliente por CI.");
            }
        });
    } else {
        $("#txt_nombres, #txt_paterno, #txt_materno, #txt_celular, #txt_direccion").val('');
    }
  });
});

// Eventos delegados para acciones de DataTables (Botones generados dinámicamente)
$(document).on('click', '.EditarContrato', function(e){
  e.preventDefault();
  $("#titulo").html("Modificar Registro");
  $("#OpcionNuevo").hide();
  $("#OpcionEditar").show("slow");

  var idcontrato = $(this).data("id");
  
  // Limpiar campos visuales antes de cargar la info
  LimpiarCamposContrato();
  document.getElementsByName("txt_idcontrato")[0].value = idcontrato;

  // Cargar datos del contrato completo por AJAX
  $.ajax({
      url: base_url + 'contrato/obtener/' + idcontrato,
      type: 'GET',
      dataType: 'json',
      success: function(response) {
          if (response.status === 'success' && response.data) {
              var d = response.data;
              
              // 1. Llenar contrato
              $("#txt_actividad").val(d.ACTIVIDAD);
              $("#txt_razon_social").val(d.RAZONSOCIAL);
              $("#txt_contrato").val(d.CONTRATO);
              $("#txt_fecha_suscripcion").val(d.FECHA_SUSCRIPCION);
              $("#txt_fecha_inicio").val(d.FECHA_INICIO);
              
              // Disparar evento para auto-calcular la frase y luego asignar el tiempo exacto guardado
              $("#txt_fecha_inicio").trigger('change');
              $("#txt_tiempo").val(d.TIEMPOCONTRATO); 

              // 2. Llenar cliente
              $("#txt_cedula").val(d.CEDULA);
              $("#txt_nombres").val(d.NOMBRES);
              $("#txt_paterno").val(d.PATERNO);
              $("#txt_materno").val(d.MATERNO);
              $("#txt_celular").val(d.CELULAR);
              $("#txt_direccion").val(d.DIRECCION);
              
              // 3. Llenar área y catálogo anidado
              if(d.IDAREA) {
                  $("#SelArea").val(d.IDAREA).trigger('change.select2');
                  
                  var $selectCatalogo = $("#SelItemCatalogo");
                  $selectCatalogo.empty().append('<option value="0">-- Cargando Catálogo... --</option>').prop('disabled', false);
                  
                  $.ajax({
                      url: base_url + 'contrato/listar_catalogo_por_area/' + d.IDAREA,
                      type: 'POST',
                      dataType: 'json',
                      success: function(catResp) {
                          var datos = catResp.data ? catResp.data : catResp;
                          $selectCatalogo.empty().append('<option value="0">-- Seleccione un Espacio / Servicio --</option>');
                          $(datos).each(function(i, v) {
                              var option = '<option value="' + v.IDCATALOGO + '" data-precio="' + v.ALQUILER + '">' + v.BESPACIO + '</option>';
                              $selectCatalogo.append(option);
                          });
                          // Seleccionar el ítem correcto que tenía el contrato y su precio
                          $selectCatalogo.val(d.IDCATALOGO).trigger('change.select2');
                          $("#txt_alquiler_ref").val(d.ALQUILER);
                      }
                  });
              }
          } else {
              Swal.fire('Error', 'No se pudo cargar los datos del contrato', 'error');
          }
      },
      error: function() {
          Swal.fire('Error', 'Error de conexión al obtener el contrato', 'error');
      }
  });
});

$(document).on('click', '.DetalleContrato', function(e){
  e.preventDefault();
  
  var idcontrato = $(this).data("id");
  
  // Cargar datos del contrato completo por AJAX para llenar el Expediente
  $.ajax({
      url: base_url + 'contrato/obtener/' + idcontrato,
      type: 'GET',
      dataType: 'json',
      success: function(response) {
          if (response.status === 'success' && response.data) {
              var d = response.data;
              var clienteText = d.NOMBRES + ' ' + (d.PATERNO ? d.PATERNO : '') + ' ' + (d.MATERNO ? d.MATERNO : '');
              
              var infoHtml = "<strong>Nro Cite/Contrato:</strong> " + d.CONTRATO + "<br>" +
                             "<strong>Arrendatario:</strong> " + clienteText + " | <strong>CI:</strong> " + d.CEDULA + "<br>" +
                             "<strong>Actividad:</strong> " + d.ACTIVIDAD + "<br>" +
                             "<strong>Periodo:</strong> " + d.FECHA_INICIO + " (Suscrito: " + (d.FECHA_SUSCRIPCION ? d.FECHA_SUSCRIPCION : 'N/A') + ")";
              $("#info_pago_contrato").html(infoHtml);

              // Manejo dinámico del panel del PDF
              var pdfHtml = '';
              if(d.ARCHIVO_PDF && d.ARCHIVO_PDF !== null && d.ARCHIVO_PDF !== '') {
                  pdfHtml = '<h6 class="text-success m-0"><i class="fas fa-file-pdf text-danger fa-2x"></i></h6>' +
                            '<small class="d-block mb-1 text-muted">Contrato Digitalizado</small>' +
                            '<a href="' + base_url + 'views/contrato/pdf/' + d.ARCHIVO_PDF + '" target="_blank" class="btn btn-outline-danger btn-sm mb-1 w-100"><i class="fas fa-eye"></i> Ver Documento</a>' +
                            '<button type="button" class="btn btn-link btn-sm text-muted p-0" onclick="mostrarFormularioPDF(' + idcontrato + ')"><small>Actualizar Archivo</small></button>';
              } else {
                  pdfHtml = '<h6 class="text-secondary m-0"><i class="fas fa-file-upload fa-2x"></i></h6>' +
                            '<small class="d-block mb-1 text-muted">Sin Respaldo Digital</small>' +
                            '<form id="form_subir_pdf" enctype="multipart/form-data">' +
                            '<input type="hidden" name="idcontrato_pdf" id="idcontrato_pdf" value="' + idcontrato + '">' +
                            '<input type="file" name="file_pdf" id="file_pdf" accept="application/pdf" class="form-control-file form-control-sm mb-1" required style="font-size: 0.75rem;">' +
                            '<button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fas fa-upload"></i> Subir PDF</button>' +
                            '</form>';
              }
              $("#panel_pdf").html(pdfHtml);
              
              ListarDetalle(idcontrato);
          }
      }
  });
});

// Función global para mostrar el formulario si el usuario quiere sobreescribir el PDF
window.mostrarFormularioPDF = function(idcontrato) {
    var pdfHtml = '<h6 class="text-primary m-0"><i class="fas fa-file-upload fa-2x"></i></h6>' +
                  '<small class="d-block mb-1 text-muted">Actualizar Respaldo</small>' +
                  '<form id="form_subir_pdf" enctype="multipart/form-data">' +
                  '<input type="hidden" name="idcontrato_pdf" value="' + idcontrato + '">' +
                  '<input type="file" name="file_pdf" id="file_pdf" accept="application/pdf" class="form-control-file form-control-sm mb-1" required style="font-size: 0.75rem;">' +
                  '<button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fas fa-upload"></i> Guardar Nuevo PDF</button>' +
                  '</form>';
    $("#panel_pdf").html(pdfHtml);
};

// Evento para subir el PDF mediante AJAX sin recargar la página
$(document).on('submit', '#form_subir_pdf', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var idcontrato = $("input[name='idcontrato_pdf']").val();
    
    Swal.fire({ title: 'Subiendo archivo...', text: 'Por favor espere.', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
    
    $.ajax({
        url: base_url + 'contrato/upload_pdf',
        type: 'POST',
        data: formData, contentType: false, processData: false, dataType: 'json',
        success: function(resp) {
            if(resp.status === 'success') {
                Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'success', title: resp.message});
                // Recargar el panel dinámicamente sin cerrar el modal
                var pdfHtml = '<h6 class="text-success m-0"><i class="fas fa-file-pdf text-danger fa-2x"></i></h6>' +
                              '<small class="d-block mb-1 text-muted">Contrato Digitalizado</small>' +
                              '<a href="' + base_url + 'views/contrato/pdf/' + resp.archivo + '" target="_blank" class="btn btn-outline-danger btn-sm mb-1 w-100"><i class="fas fa-eye"></i> Ver Documento</a>' +
                              '<button type="button" class="btn btn-link btn-sm text-muted p-0" onclick="mostrarFormularioPDF(' + idcontrato + ')"><small>Actualizar Archivo</small></button>';
                $("#panel_pdf").html(pdfHtml);
            } else { Swal.fire('Error', resp.message, 'error'); }
        },
        error: function() { Swal.fire('Error', 'Problema de conexión al subir el archivo.', 'error'); }
    });
});

$(document).on('click', '.EliminarContrato', function(e){
  e.preventDefault();
  var idcontrato = $(this).data("id");
  var registro = $(this).data("contrato") + ' ' + $(this).data("actividad");
  Swal.fire({
    title: 'Está seguro de Eliminar este Registro?',
    text: registro+" / Esta operación NO podrá Revertirse",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Eliminar el Registro!'
  }).then((result) => {
    if (result.isConfirmed) {
      CrudContrato('contrato/delete/'+idcontrato);
    }
  });
});

$(document).on('click', '.ConfirmarContrato', function(e){
  e.preventDefault();
  var idcontrato = $(this).data("id");
  var registro = $(this).data("contrato") + ' ' + $(this).data("actividad");
  Swal.fire({
    title: 'Está seguro de Confirmar este Registro de Contrato?',
    text: registro+" / Esta operación NO podrá Revertirse",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Confirmar Contrato!'
  }).then((result) => {
    if (result.isConfirmed) {
      CrudContrato('contrato/confirmar/'+idcontrato);
    }
  });
});

function ListarContrato(){
  $("#TablaContrato").DataTable({     
    "responsive":true,
   "destroy":true, 
   "order": [],
   "autoWidth": false,
      "ajax":{
        "url": base_url+'contrato/listar',
        "dataSrc": function(json) { 
            if (json.status === 'error') {
                console.error("Error backend BD:", json.message);
            }
            return json.data ? json.data : []; 
        }       
      },
      "columns":[
  {"data": null, "searchable": false, "orderable": false, "render": function (data, type, row, meta) {
      return meta.row + 1; // Contador automático (1, 2, 3...)
  }},
      {"data": "REPRESENTANTE"},
      {"data": "ACTIVIDAD"},
      {"data": "RAZONSOCIAL"},
      {"data": "CONTRATO", "render": function(data, type, row) {
          var estado = row.VIGENTE === 'PR' ? 
              '<br><span class="badge badge-warning mt-1"><i class="fas fa-clock"></i> Pre-Registro (Sin Confirmar)</span>' : 
              '<br><span class="badge badge-success mt-1"><i class="fas fa-check-circle"></i> Vigente</span>';
          return "<strong>" + data + "</strong>" + estado;
      }},
      {"data": "FECHA_INICIO"},
      {"data": "FECHA_SUSCRIPCION"},
      {"data": "FECHA_INICIO", "render": function(data, type, row) {
          if (!data) return row.TIEMPOCONTRATO + " meses";
          var partes = data.split('-');
          var anio = parseInt(partes[0]);
          var mes = parseInt(partes[1]);
          var dia = parseInt(partes[2]);
          
          var diasEnMes = new Date(anio, mes, 0).getDate();
          var diasRestantes = diasEnMes - dia + 1;
          var mesesRestantes = 12 - mes;
          
          if (diasRestantes === diasEnMes) {
              mesesRestantes += 1;
              diasRestantes = 0;
          }
          
          var textoMeses = mesesRestantes > 0 ? mesesRestantes + (mesesRestantes === 1 ? " mes" : " meses") : "";
          var textoDias = diasRestantes > 0 ? diasRestantes + (diasRestantes === 1 ? " día" : " días") : "";
          var union = (mesesRestantes > 0 && diasRestantes > 0) ? ", " : "";
          
          return textoMeses + union + textoDias;
      }},
      {"data": "MONTO"},
      {"data": null, "render": function(data, type, row) {
          var atributos = "data-id='"+row.IDARRIENDO+"' data-contrato='"+row.CONTRATO+"' data-actividad='"+row.ACTIVIDAD+"'";
          // Botones con title para tooltip y sin texto, usando iconos actualizados
          var boton_editar = "<button type='button' class='EditarContrato btn btn-warning btn-sm mx-1' "+atributos+" data-toggle='modal' data-target='#ModalContrato' title='Editar Contrato'><i class='fas fa-edit'></i></button>";
          var boton_detalle = "<button type='button' class='DetalleContrato btn btn-info btn-sm mx-1' "+atributos+" data-toggle='modal' data-target='#ModalDetalle' title='Expediente y Pagos'><i class='fas fa-folder-open'></i></button>";
          var boton_eliminar = "<button type='button' class='EliminarContrato btn btn-danger btn-sm mx-1' "+atributos+" title='Eliminar Contrato'><i class='fas fa-trash'></i></button>";
          
          var boton_confirmar = "";
          // Si el contrato está en Pre-Registro (PR), mostramos el botón de Confirmar
          if(row.VIGENTE === 'PR') {
              boton_confirmar = "<button type='button' class='ConfirmarContrato btn btn-success btn-sm mx-1' "+atributos+" title='Confirmar Contrato'><i class='fas fa-thumbs-up'></i></button>";
          }
          
          return "<div class='text-nowrap text-center'>" + boton_editar + boton_detalle + boton_confirmar + boton_eliminar + "</div>";
      }}
      ]
    }); 
} // fin de funcion listarContrato