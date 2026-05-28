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
   "order": [],
   "autoWidth": false,
      "ajax":{
        "url": ruta,
        "dataSrc": function(json) { return json.data ? json.data : json; }       
      },
      "columns":[
      {"data": "IDPAGO"},
      {"data": "PERIODO"},
      {"data": "MONTO"},
      {"data": "PENDIENTE", "render": function(data) {
          return data === 'SI' ? '<span class="badge badge-warning">PENDIENTE</span>' : '<span class="badge badge-success"><i class="fas fa-check-double"></i> PAGADO</span>';
      }},
      {"data": null, "render": function(data, type, row) {
          if(row.PENDIENTE === 'SI') {
              return "<button class='btn btn-success btn-sm PagarCuota' data-id='"+row.IDPAGO+"' data-periodo='"+row.PERIODO+"' data-monto='"+row.MONTO+"'><i class='fas fa-dollar-sign'></i> Pagar</button>";
          }
          return "<span class='text-success'><i class='fas fa-check'></i> Completado</span>";
      }}
      ],
       dom: 'Bfrtip',
       buttons: [
             'excel', 'pdf', 'print'
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

  // Evento para realizar el Pago de una Cuota
  $(document).on('click', '.PagarCuota', function(e) {
      e.preventDefault();
      var idpago = $(this).data('id');
      var periodo = $(this).data('periodo');
      var monto = $(this).data('monto');
      
      Swal.fire({
          title: '¿Confirmar Pago?',
          text: 'Se registrará el pago del periodo ' + periodo + ' por Bs. ' + monto,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, Pagar!'
      }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: base_url + 'pagos/realizar_pago/' + idpago,
                  type: 'POST',
                  dataType: 'json',
                  success: function(resp) {
                      if(resp.status === 'success') {
                          Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'success', title: 'Pago registrado.'});
                          $('#TablaDetalle').DataTable().ajax.reload();
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

  var idcontrato = $(this).parents("tr").find("td").eq(0).html();
  
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
  
  var idcontrato = $(this).parents("tr").find("td").eq(0).html();
  var clienteText = $(this).parents("tr").find("td").eq(2).html();
  var contratoText = $(this).parents("tr").find("td").eq(5).html();
  
  $("#info_pago_contrato").html("<strong>Nro Contrato:</strong> " + contratoText + " | <strong>Arrendatario:</strong> " + clienteText);
  ListarDetalle(idcontrato);
});

$(document).on('click', '.EliminarContrato', function(e){
  e.preventDefault();
  var registro=$(this).parents("tr").find("td").eq(5).html()+' '+$(this).parents("tr").find("td").eq(3).html();
  Swal.fire({
    title: 'Está seguro de Eliminar este Registro?',
    text: registro+" / Esta operación NO podrá Revertirse",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Eliminar el Registro!'
  }).then((result) => {
    if (result.isConfirmed) {
      CrudContrato('contrato/delete/'+$(this).parents("tr").find("td").eq(0).html());
    }
  });
});

$(document).on('click', '.ConfirmarContrato', function(e){
  e.preventDefault();
  var registro=$(this).parents("tr").find("td").eq(5).html()+' '+$(this).parents("tr").find("td").eq(3).html();
  Swal.fire({
    title: 'Está seguro de Confirmar este Registro de Contrato?',
    text: registro+" / Esta operación NO podrá Revertirse",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Confirmar Contrato!'
  }).then((result) => {
    if (result.isConfirmed) {
      CrudContrato('contrato/confirmar/'+$(this).parents("tr").find("td").eq(0).html());
    }
  });
});

function ListarContrato(){
  var boton_editar="<button type='button' class='EditarContrato btn btn-warning btn-sm' data-toggle='modal' data-target='#ModalContrato' ><i class='fas fa-edit'></i></button>";
  var boton_detalle="<button type='button' class='DetalleContrato btn btn-info btn-sm' data-toggle='modal' data-target='#ModalDetalle'><i class='fas fa-table'></i> Detalle</button>";
  var boton_confirmar="<button type='button' class='ConfirmarContrato btn btn-success btn-sm'><i class='fas fa-thumbs-up'></i> Confirmar</button>";
  var boton_eliminar="<button type='button' class='EliminarContrato btn btn-danger btn-sm'><i class='fas fa-trash'></i></button>";
  
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
      {"data": "IDARRIENDO"},
      {"data": "IDCLIENTE"},
      {"data": "REPRESENTANTE"},
      {"data": "ACTIVIDAD"},
      {"data": "RAZONSOCIAL"},
      {"data": "CONTRATO"},
      {"data": "FECHA_INICIO"},
      {"data": "FECHA_SUSCRIPCION"},
      {"data": "TIEMPOCONTRATO"},
      {"data": "MONTO"},
      {"defaultContent":boton_editar+" "+boton_detalle+" "+boton_confirmar+" "+boton_eliminar}
      ],
       dom: 'Bfrtip',
       buttons: [
             'excel', 'pdf', 'print'
        ]       
    }); 
} // fin de funcion listarContrato