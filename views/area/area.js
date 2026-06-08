//***** funciones de area *****

function Insert_Area() {
  AccionAjax(base_url+'area/add', $("#FormArea").serialize(), ListarArea, 'Registro Insertado Con Éxito');
  $('#ModalArea').modal('hide');
}

function LimpiarCamposArea() {
  document.getElementsByName("txt_referencia")[0].value = "";
  document.getElementsByName("txt_ubicacion")[0].value = "";
}

function Editar_Area() {
  AccionAjax(base_url+'area/edit', $("#FormArea").serialize(), ListarArea, 'Registro Modificado Con Éxito');
  $('#ModalArea').modal('hide');
}

function EliminarArea(id) {
  AccionAjax(base_url+'area/delete/'+id, null, ListarArea, 'Registro Eliminado con Éxito');
}

function ListarArea() {
  var boton_editar="<button type='button' class='EditarArea btn btn-warning btn-sm' data-toggle='modal' data-target='#ModalArea' title='Editar'><i class='fas fa-edit'></i></button>";
  var boton_eliminar="<button type='button' class='EliminarArea btn btn-danger btn-sm' title='Eliminar'><i class='fas fa-trash'></i></button>";
  
  $("#TablaArea").DataTable({     
    "responsive": true,
    "destroy": true, 
    "order": [],
    "autoWidth": false,
    "ajax": {
      "url": base_url+'area/listar',
      "dataSrc": function(json) { return json.data ? json.data : json; }
    },
    "columns": [
      {
        "data": "IDAREA",
        "render": function(data, type, row, meta) {
          return '<span class="d-none id-row">' + data + '</span>' + (meta.row + 1);
        }
      },
      {"data": "REFERENCIA"},
      {"data": "UBICACION"},
      {"defaultContent": boton_editar+" "+boton_eliminar}
    ]
  }); 
}

// ***** Inicialización y Eventos *****
$(document).ready(function(){
  // Cargar datos iniciales
  ListarArea();

  // Evento: Botón Registrar
  $("#btn_InsertArea").on('click', function(e){
    e.preventDefault();
    if(!$('#FormArea')[0].checkValidity()) {
        $('#FormArea')[0].reportValidity();
        return;
    }
    Insert_Area();
  });

  // Evento: Botón Guardar Cambios (Editar)
  $("#btn_EditarArea").on('click', function(e){
    e.preventDefault();
    if(!$('#FormArea')[0].checkValidity()) {
        $('#FormArea')[0].reportValidity();
        return;
    }
    Editar_Area();
  });

  // Evento: Botón Nuevo Registro
  $("#btnNuevoRegistro").on('click', function(e){
    e.preventDefault();
    $("#titulo").html("Nuevo Registro de Áreas y Ubicación");
    $("#OpcionEditar").hide();
    $("#OpcionNuevo").show("slow");
    LimpiarCamposArea();
  });

  // Evento: Clic en Editar en la tabla
  $(document).on('click', '.EditarArea', function(e){
    e.preventDefault();
    $("#titulo").html("Modificar Registro");
    $("#OpcionNuevo").hide();
    $("#OpcionEditar").show("slow");

    document.getElementsByName("txt_idarea")[0].value=$(this).parents("tr").find(".id-row").text();
    document.getElementsByName("txt_referencia")[0].value=$(this).parents("tr").find("td").eq(1).html();
    document.getElementsByName("txt_ubicacion")[0].value=$(this).parents("tr").find("td").eq(2).html(); 
  });

  // Evento: Clic en Eliminar en la tabla
  $(document).on('click', '.EliminarArea', function(e){
    e.preventDefault();
    var registro=$(this).parents("tr").find("td").eq(1).html()+' -> '+$(this).parents("tr").find("td").eq(2).html();
    Swal.fire({
      title: '¿Está seguro de Eliminar este Registro?',
      text: registro+" / Esta operación NO podrá Revertirse",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, Eliminar!'
    }).then((result) => {
      if (result.isConfirmed) { EliminarArea($(this).parents("tr").find(".id-row").text()); }
    });
  });
});