
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-store-alt"></i> 
             <strong>Administración/Almacén y Tiendas Habilitadas</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-primary btn-lm" data-toggle="modal" data-target="#ModalAlmacenTienda">
                <i class="fas fa-plus-circle"></i> 
                 Nuevo Registro
               </button>
            </span>
            </ol>
          </div>
        </div>
         
    </div><!-- /.container-fluid -->
  </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
             
              <!-- /.cargar el listado de la tabla -->
              <div class="card-body" id="listado">

                <table id="tabla_almacen"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">
                  <tr>
                    <th>Nro</th>
                    <th>Descripción</th>
                    <th>Ubicación</th>
                    <th>Contactos</th>
                    <th>Tipo</th>
                    <th>Activo</th>
                    <th>Acciones</th>                    
                  </tr>
                </thead>
                 
                  <tfoot class=" table-success">
                  <tr>
                    <th>Nro</th>
                    <th>Descripción</th>
                    <th>Ubicación</th>
                    <th>Contactos</th>
                    <th>Tipo</th>
                    <th>Activo</th>
                    <th>Acciones</th>                    
                  </tr>
                  </tfoot>
                
                </table>

               </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
     
    </section>
    <!-- /.content -->
  

     <!-- Modal -->
        <div id="ModalAlmacenTienda" class="modal fade" role="dialog">

          <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titulo">Registro de Almacén/Tiendas</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
              <div class="modal-body">
                <p class="statusMsg"></p>
                 <form role="form" enctype="multipart/form-data" id="Form_Tienda" method="POST" autocomplete="off">
                            <div class="form-group">
                              <input  type="hidden"  id="txt_id" name="txt_id"/>
                            </div>

                            <div class="form-group">
                                <label for="txt_descripcion">Descripción</label>
                                <input type="text" class="form-control" id="txt_descripcion" name="txt_descripcion" required  minlength="5" maxlength="30" placeholder="Ingrese la Descripcion"  />
                            </div>

                            <div class="form-group">
                                <label for="txt_ubicacion">Ubicación</label>
                                <input type="text" class="form-control" id="txt_ubicacion" name="txt_ubicacion" 
                                required  minlength="5" maxlength="100" placeholder="Ingrese la ubicación geográfica"/>
                            </div>
                            
                            <div class="row">
                               <div class="col-sm-6">
                                 <label for="txt_contactos">Números de Contacto</label>
                                 <input type="text" class="form-control" id="txt_contactos" name="txt_contactos" required  minlength="8" maxlength="20" placeholder="Nro Contacto 1 - Nro Contacto 2"/>
                               </div>

                               <div class="col-sm-6">
                                 <label for="txt_tipo">Tipo de Ambiente</label>
                                 <select class="form-control" id="txt_tipo" name="txt_tipo">
                                    <option selected>Seleccione un Dato</option>
                                    <option value="TIENDA">Tienda</option>
                                    <option value="ALMACEN">Almacén</option>
                                 </select>
                               </div>
                            </div>                    
                    </form>
              </div>
              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_insert_tienda" name="btn_insert_tienda" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Registrar                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_editar_tienda" name="btn_editar_tienda" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Guardar Cambios                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>


            </div>
          </div>
        </div>  <!--FIN Modal -->

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_insert_tienda").on('click', function(e){
      e.preventDefault();
       Insert_Tienda();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_editar_tienda").on('click', function(e){
      e.preventDefault();
       Editar_Tienda();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btnNuevoRegistro").on('click', function(e){
      e.preventDefault();
       $("#titulo").html("Registro de Almacén/Tiendas");
       $("#OpcionEditar").hide();
       $("#OpcionNuevo").show("slow");
       LimpiarCamposTienda();
    });
  });
</script>

<script type="text/javascript">
   $(document).ready(function(){
    ListarTienda();  
   });
</script>

<script type="text/javascript">
// selecciona un registro para editar
  $(document).on('click', '.EditarAlmacen', function(e){
 e.preventDefault();

 $("#titulo").html("Modificar Registro");
 $("#OpcionNuevo").hide();
 $("#OpcionEditar").show("slow");

document.getElementsByName("txt_id")[0].value = $(this).parents("tr").find("td").eq(0).html();
document.getElementsByName("txt_descripcion")[0].value = $(this).parents("tr").find("td").eq(1).html();
document.getElementsByName("txt_ubicacion")[0].value = $(this).parents("tr").find("td").eq(2).html(); 
document.getElementsByName("txt_contactos")[0].value = $(this).parents("tr").find("td").eq(3).html();
document.getElementsByName("txt_tipo")[0].value = $(this).parents("tr").find("td").eq(4).html(); 
});
</script>

<script type="text/javascript">
// selecciona un registro para eliminar
  $(document).on('click', '.EliminarAlmacen', function(e){
 e.preventDefault();
    Swal.fire({
      title: 'Está seguro de Eliminar este Registro?',
      text: "Esta operación NO podrá Revertirse",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Eliminar el Registro!'
    }).then((result) => {
                            if (result.isConfirmed) {
                               EliminarAlmacen($(this).parents("tr").find("td").eq(0).html());    
                            }
                       })
});
</script>
