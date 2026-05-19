//***** funciones de usuario

function Insert_Usuario(){
  AccionAjax(base_url+'usuario/add', $("#FormUsuario").serialize(), ListarUsuario, 'Registro Insertado Con Éxito');
} // final funcion Insert_Usuario

function LimpiarCamposUsuario()
{
  // pone en blanco todos los campos de ingreso de datos de personal
    document.getElementsByName("txt_nombre")[0].value = "";
    document.getElementsByName("txt_usuario")[0].value = "";
    document.getElementsByName("txt_clave")[0].value = "";
    $('#txt_idrol').val('').trigger('change');    
} // fin LimpiarCamposUusario()

function Editar_Usuario(){
  AccionAjax(base_url+'usuario/edit', $("#FormUsuario").serialize(), ListarUsuario, 'Registro Modificado Con Éxito');
} // final funcion Editar_Usuario

function ReactivarUsuario(id)
{
  AccionAjax(base_url+'usuario/reactivar/'+id, null, ListarUsuario, 'El Usuario fue Reactivado con Éxito');
} // fin reactivar Usuario

function BajaUsuario(id)
{
  AccionAjax(base_url+'usuario/baja/'+id, null, ListarUsuario, 'El Usuario fue Inactivado con Éxito');
} // fin baja  Usuario


function EliminarUsuario(id)
{
  AccionAjax(base_url+'usuario/delete/'+id, null, ListarUsuario, 'Registro Eliminado con Éxito');
} // fin eliminar Usuario

function ListarUsuario(){
  var user_rol = $("#user_rol_sesion").val();
  var id_usuario_sesion = $("#id_usuario_sesion").val();

  var boton_editar="<button type='button' class='EditarUsuario btn btn-warning btn-sm' data-toggle='modal' data-target='#ModalUsuario' ><i class='fas fa-edit'></i></button>";
  var boton_baja="<button type='button' class='BajaUsuario btn btn-danger btn-sm' title='Inactivar'><i class='fas fa-trash'></i></button>";
  var boton_reactivar="<button type='button' class='ReactivarUsuario btn btn-success btn-sm' title='Reactivar'><i class='fas fa-check'></i></button>";
  $("#TablaUsuario").DataTable({     
 
   "responsive":true,
   "destroy":true, 
   "order": [],
   "autoWidth": false,
      "ajax":{
        "url": base_url+'usuario/listar',
        "dataSrc":""
      },
      "columns":[
      {
        "data": "IDUSUARIO",
        "render": function (data, type, row, meta) {
          return '<span class="d-none id-row">' + data + '</span>' + (meta.row + 1);
        }
      },
      {"data": "NOMBRE"},
      {"data": "USR"},
      {"data": "FECHA_ALTA"},
      {"data": "FECHA_BAJA"},
      {
        "data": "ROL_DESCRIPCION",
        "render": function(data, type, row, meta) {
          return `<span data-idrol="${row.IDROL}">${data || 'No asignado'}</span>`;
        }
      },
      {"data": "ACTIVO"},
      {"data": "ACTIVO",
         "render": function ( data, type, row, meta ) {
          if (data=='SI') {
            var acciones = boton_editar;
            if (row.IDUSUARIO != id_usuario_sesion) {
              acciones += " " + boton_baja;
            }
            return acciones;
          } else {
            if(user_rol === 'AD' || user_rol === 'ADM') {
                return boton_reactivar;
            }
            return "<span class='badge badge-danger'>Inactivo</span>";
          }
          }
      }

      ],
       dom: 'Bfrtip',
       buttons: [
            'copy', 'excel', 'pdf', 'print'
        ]       
    }); 
} // fin de funcion listarUsuario

function LlenarRolesUsuario()
{ 
  const ruta = base_url + 'usuario/listar_roles';  
  $.ajax({
    url: ruta,
    type: 'POST',
    dataType: 'json',
    success: function(e){
        $("#txt_idrol").empty();
        $("#txt_idrol").append('<option value="" disabled selected>Seleccione un Rol</option>');
        $(e).each(function(i, v){
            $("#txt_idrol").append('<option value="' + v.IDROL + '">' + v.DESCRIPCION + '</option>');
        });
     }
  });  
} // final funcion LlenarRolesUsuario

// ***** Inicialización y Eventos *****
$(document).ready(function(){
  // Cargar datos iniciales
  ListarUsuario();  
  LlenarRolesUsuario();

  // Evento: Botón Registrar
  $("#btn_InsertUsuario").on('click', function(e){
    e.preventDefault();
    Insert_Usuario();
  });

  // Evento: Botón Guardar Cambios (Editar)
  $("#btn_EditarUsuario").on('click', function(e){
    e.preventDefault();
    Editar_Usuario();
  });

  // Evento: Botón Nuevo Registro
  $("#btnNuevoRegistro").on('click', function(e){
    e.preventDefault();
    $("#titulo").html("Nuevo Registro de Accesos");
    $("#header_modal_usuario").removeClass("bg-warning text-dark").addClass("bg-primary text-white");
    $("#OpcionEditar").hide();
    $("#OpcionNuevo").show("slow");
    LimpiarCamposUsuario();
  });

  // Evento: Clic en Editar en la tabla
  $(document).on('click', '.EditarUsuario', function(e){
    e.preventDefault();
    $("#titulo").html("Modificar Registro");
    $("#header_modal_usuario").removeClass("bg-primary text-white").addClass("bg-warning text-dark");
    $("#OpcionNuevo").hide();
    $("#OpcionEditar").show("slow");

    document.getElementsByName("txt_idusuario")[0].value=$(this).parents("tr").find(".id-row").text();
    document.getElementsByName("txt_nombre")[0].value=$(this).parents("tr").find("td").eq(1).html();
    document.getElementsByName("txt_usuario")[0].value=$(this).parents("tr").find("td").eq(2).html();
    document.getElementsByName("txt_clave")[0].value="";
    $('#txt_idrol').val($(this).parents("tr").find("td").eq(5).find('span').data('idrol')).trigger('change');
  });

  // Evento: Clic en Reactivar en la tabla
  $(document).on('click', '.ReactivarUsuario', function(e){
    e.preventDefault();
    var registro=$(this).parents("tr").find("td").eq(2).html()+' '+$(this).parents("tr").find("td").eq(1).html();
    Swal.fire({
      title: '¿Está seguro de Reactivar este Usuario?',
      text: registro,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Reactivar Usuario!'
    }).then((result) => {
      if (result.isConfirmed) { ReactivarUsuario($(this).parents("tr").find(".id-row").text()); }
    });
  });

  // Evento: Clic en Inactivar en la tabla
  $(document).on('click', '.BajaUsuario', function(e){
    e.preventDefault();
    var registro=$(this).parents("tr").find("td").eq(2).html()+' '+$(this).parents("tr").find("td").eq(1).html();
    Swal.fire({
      title: '¿Está seguro de Inactivar este Usuario?',
      text: registro+" / El usuario perderá acceso al sistema temporalmente",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#f39c12',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, Inactivar!'
    }).then((result) => {
      if (result.isConfirmed) { BajaUsuario($(this).parents("tr").find(".id-row").text()); }
    });
  });
});