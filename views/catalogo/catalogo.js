// *********funciones Catalogo********
function Insert_Catalogo(){
  AccionAjax(base_url+'catalogo/add', $("#FormCatalogo").serialize(), ListarCatalogo, 'Registro Insertado Con Éxito');
} // final funcion Insert_CATALOGO

function LimpiarCamposCatalogo(){
// pone en blanco todos los campos de ingreso de datos de Tienda/almacen
$('#SelBuscarArea').val('0').trigger('change');
document.getElementsByName("txt_descripcion")[0].value = "";
document.getElementsByName("txt_alquiler")[0].value = "0";
}

function Editar_Catalogo(){
  AccionAjax(base_url+'catalogo/edit', $("#FormCatalogo").serialize(), ListarCatalogo, 'Registro Modificado Con Éxito');
} // final funcion EDITAR catalogo

function EliminarCatalogo(id)
{
  AccionAjax(base_url+'catalogo/delete/'+id, null, ListarCatalogo, 'Registro Eliminado con Éxito');
} // fin eliminar catalogo

function LlenarAreas()
{ 
  const ruta=base_url+'catalogo/listar_areas';  
  $.ajax({
    url:ruta,
    type:'POST',
    dataType: 'json',
    success: function(e){
        $(e).each(function(i, v){ // indice, valor
                       $("#SelBuscarArea").append('<option value="' + v.IDAREA + '">' + v.DISTRIBUCION + '</option>');
                               });
     }
});  
} // final funcion Llenar_Areas combobox

function ListarCatalogo(){
  var boton_editar="<button type='button' class='EditarCatalogo btn btn-warning btn-sm' data-toggle='modal' data-target='#ModalCatalogo' title='Editar'><i class='fas fa-edit'></i></button>";
  var boton_eliminar="<button type='button' class='EliminarCatalogo btn btn-danger btn-sm' title='Eliminar'><i class='fas fa-trash'></i></button>";      
  $("#TablaCatalogo").DataTable({     
   "responsive":true,
  "destroy":true, 
  "order": [],
   "autoWidth": false,
      "ajax":{
        "url": base_url+'catalogo/listar',
        "dataSrc":""
      },
      "columns":[
      {
        "data": "IDCATALOGO",
        "render": function(data, type, row, meta) {
          return '<span class="d-none id-row">' + data + '</span><span class="d-none id-area">' + row.IDAREA + '</span>' + (meta.row + 1);
        }
      },
      {"data": "DISTRIBUCION"},
      {"data": "DESCRIPCION"},
      {"data": "ALQUILER"},
      {"data": "ESTADO"},
      {"data": "ESTADO",
         "render": function ( data, type, row, meta ) {
          if (data=='DISPONIBLE')
            return boton_editar+" "+boton_eliminar;
          else
            return "";
          }
      }

      //{"defaultContent": boton_editar+" "+boton_eliminar}
      ],
       dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf', 'print'
        ]        
    }); 
} // fin de funcion catalogo

// ***** Inicialización y Eventos *****
$(document).ready(function(){
  // Cargar datos iniciales
  ListarCatalogo();    
  LlenarAreas();
  $("#SelBuscarArea").select2();

  // Evento: Botón Registrar
  $("#btn_InsertCatalogo").on('click', function(e){
    e.preventDefault();
    Insert_Catalogo();
  });

  // Evento: Botón Guardar Cambios (Editar)
  $("#btn_EditarCatalogo").on('click', function(e){
    e.preventDefault();
    Editar_Catalogo();
  });

  // Evento: Botón Nuevo Registro
  $("#btnNuevoRegistro").on('click', function(e){
    e.preventDefault();
    $("#titulo").html("Nuevo Registro de Servicios");
    $("#header_modal_catalogo").removeClass("bg-warning text-dark").addClass("bg-primary text-white");
    $("#OpcionEditar").hide();
    $("#OpcionNuevo").show("slow");
    LimpiarCamposCatalogo();
  });

  // Evento: Clic en Editar en la tabla
  $(document).on('click', '.EditarCatalogo', function(e){
    e.preventDefault();
    $("#titulo").html("Modificar Registro");
    $("#header_modal_catalogo").removeClass("bg-primary text-white").addClass("bg-warning text-dark");
    $("#OpcionNuevo").hide();
    $("#OpcionEditar").show("slow");

    document.getElementsByName("txt_idcatalogo")[0].value=$(this).parents("tr").find(".id-row").text();
    $('#SelBuscarArea').val($(this).parents("tr").find(".id-area").text()).trigger('change');
    document.getElementsByName("txt_descripcion")[0].value=$(this).parents("tr").find("td").eq(2).html(); 
    document.getElementsByName("txt_alquiler")[0].value=$(this).parents("tr").find("td").eq(3).html(); 
  });

  // Evento: Clic en Eliminar en la tabla
  $(document).on('click', '.EliminarCatalogo', function(e){
    e.preventDefault();
    var registro=$(this).parents("tr").find("td").eq(1).html()+' -> '+$(this).parents("tr").find("td").eq(3).html();
    Swal.fire({
      title: '¿Está seguro de Eliminar este Registro?',
      text: registro+" / Esta operación NO podrá Revertirse",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, Eliminar!'
    }).then((result) => {
      if (result.isConfirmed) { EliminarCatalogo($(this).parents("tr").find(".id-row").text()); }
    });
  });
});
