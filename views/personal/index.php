
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-users"></i> 
             <strong>Configuración/Accesos</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-primary btn-lm" data-toggle="modal" data-target="#ModalUsuario">
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

                <table id="TablaUsuario"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Fecha Alta</th>
                    <th>Fecha Baja</th>
                    <th>Rol</th> 
                    <th>Activo</th> 
                    <th>Acciones</th>
                  </tr>
                </thead>
                 
                  <tfoot class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Fecha Alta</th>
                    <th>Fecha Baja</th>
                    <th>Rol</th> 
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
        <div id="ModalUsuario" class="modal fade" role="dialog">

          <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titulo">Registro de Accesos</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
              <div class="modal-body">
                <p class="statusMsg"></p>
                 <form role="form" enctype="multipart/form-data" id="FormPersonal"  method="POST" autocomplete="off">
                            <div class="form-group">
                              <input  type="hidden"  id="txt_idusuario" name="txt_idusuario">
                            </div>
                            

                          <div class="row">
                            <div class="col-sm-12">
                                <label for="txt_nombre">Nombre Completo</label>
                                <input type="text" class="form-control" id="txt_nombre" name="txt_nombre" required  minlength="5" maxlength="50" placeholder="escriba el Nombre Completo"  />
                            </div>                           
                          </div>  
                         
                         <div class="row">
                            <div class="col-sm-4">
                                <label for="txt_usuario">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="txt_usuario" name="txt_usuario" 
                                required  minlength="5" maxlength="20" placeholder="Escriba el usuario"/>
                            </div>

                            <div class="col-sm-4">
                                <label for="txt_clave">Clave</label>
                                <input type="text" class="form-control" id="txt_clave" name="txt_clave" required  minlength="5" maxlength="15" placeholder="400001"  />
                            </div>

                            <div class="col-sm-4">
                                <label for="txt_rol">Rol de Usuario</label>
                                <select class="form-control" id="txt_rol" name="txt_rol">
                                  <option value="AD">Administrador</option>
                                  <option value="RP">Reportes</option>
                                  <option value="RG">Registros</option>
                                  <option value="PA">Pagos</option>
                                 </select>
                            </div>                               
                    </form>
              </div>
              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_InsertUsuario" name="btn_InsertUsuario" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Registrar                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_EditarUsuario" name="btn_EditarUsuario" data-dismiss="modal">
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
    $("#btn_InsertUsuario").on('click', function(e){
      e.preventDefault();
       Insert_Personal();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_EditarPersonal").on('click', function(e){
      e.preventDefault();
       Editar_Personal();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btnNuevoRegistro").on('click', function(e){
      e.preventDefault();
       $("#titulo").html("Registro de Accesos");
       $("#OpcionEditar").hide();
       $("#OpcionNuevo").show("slow");
       LimpiarCamposPersonal();
    });
  });
</script>

<script type="text/javascript">
   $(document).ready(function(){
    ListarPersonal();  
   });
</script>

<script type="text/javascript">
// selecciona un registro para editar
  $(document).on('click', '.EditarPersonal', function(e){
 e.preventDefault();

 $("#titulo").html("Modificar Registro");
 $("#OpcionNuevo").hide();
 $("#OpcionEditar").show("slow");

 document.getElementsByName("txt_idpersona")[0].value=$(this).parents("tr").find("td").eq(0).html();
 document.getElementsByName("txt_nombres")[0].value=$(this).parents("tr").find("td").eq(2).html();
 document.getElementsByName("txt_apellidos")[0].value=$(this).parents("tr").find("td").eq(1).html();
 document.getElementsByName("txt_cedula")[0].value=$(this).parents("tr").find("td").eq(3).html();
 document.getElementsByName("txt_telefonos")[0].value=$(this).parents("tr").find("td").eq(5).html();
 document.getElementsByName("txt_correo")[0].value=$(this).parents("tr").find("td").eq(6).html();
 document.getElementsByName("txt_direccion")[0].value=$(this).parents("tr").find("td").eq(4).html();

});
</script>

<script type="text/javascript">
// selecciona un registro para eliminar
  $(document).on('click', '.EliminarPersonal', function(e){
 e.preventDefault();
 var registro=$(this).parents("tr").find("td").eq(2).html()+' '+$(this).parents("tr").find("td").eq(1).html();
   registro=registro+' cedula:'+$(this).parents("tr").find("td").eq(3).html();
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
                               EliminarPersonal($(this).parents("tr").find("td").eq(0).html());    
                            }
                       })
});
</script>
