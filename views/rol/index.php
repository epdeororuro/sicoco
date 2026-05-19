
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fa fa-key"></i> 
             <strong>Administración/Roles de Acceso</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-primary btn-lm" data-toggle="modal" data-target="#ModalEncargado">
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

                <table id="TablaEncargado"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">
                  <tr>
                    <th>Nro</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>                    
                    <th>Alta</th>
                    <th>Baja</th>
                    <th>Activo</th> 
                    <th>Acciones</th>                   
                  </tr>
                </thead>
                 
                  <tfoot class=" table-success">
                  <tr>
                    <th>Nro</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>                    
                    <th>Alta</th>
                    <th>Baja</th>
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
        <div id="ModalEncargado" class="modal fade" role="dialog">

          <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titulo">Registro de Roles de Acceso</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
              <div class="modal-body">
                <p class="statusMsg"></p>
               <form role="form" enctype="multipart/form-data" id="FormEncargado"  method="POST" autocomplete="off">
                            <div class="form-group">
                              <input  type="hidden"  id="txt_idencargado" name="txt_idencargado" value="0" />
                            </div>

                            <div class="row table-info"> <h4>Datos de Personal Operativo</h4> </div>
                            <div class="col-sm-12">
                                <label for="SelBuscarPersona">Nombre Completo de Operador</label>
                                
                                      <select id="SelBuscarPersona" name="SelBuscarPersona" class="form-control" style="width:100%">
                                        <option value="0">Seleccionar un Nombre de Operador</option>
                                      </select>
                                                              
                            </div>

                            <div class="row table-info"> <h4>Asignación de Roles</h4> </div>

                           <div class="row">
                            <div class="col-sm-6">
                                <label for="txt_usuario">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="txt_usuario" name="txt_usuario" required  minlength="5" maxlength="15" placeholder="nombre.apellido"  />
                            </div>

                            <div class="col-sm-6">
                                 <label for="txt_rol">Rol de Acceso</label>
                                 <select name="txt_rol" id="txt_rol" class="form-control">
                                        <option value="0">Seleccione un Rol para Asignar</option>
                                        <option value="ADM">Administrador</option>
                                        <option value="ALM">Almacén</option>
                                        <option value="VTA">Venta</option>
                                 </select>
                            </div>                       
                          </div>
                                              
                    </form>
              </div>
              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_InsertEncargado" name="btn_InsertEncargado" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Registrar                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_EditarPersonal" name="btn_EditarPersonal" data-dismiss="modal">
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
    $("#btn_InsertEncargado").on('click', function(e){
      e.preventDefault();
       InsertEncargados();
    });
  });
</script>



<script type="text/javascript">
  $(document).ready(function(){
    $("#btnNuevoRegistro").on('click', function(e){
      e.preventDefault();
       $("#titulo").html("Registro de Roles de Acceso");
       $("#OpcionEditar").hide();
       $("#OpcionNuevo").show("slow");
       LimpiarCamposEncargado();          
    });
  });
</script>

<script type="text/javascript">
   $(document).ready(function(){
    ListarEncargado();  
     $("#SelBuscarPersona").select2(LlenarBuscadorPersonas());  
   });
</script>


<script type="text/javascript">
// selecciona un registro para eliminar
  $(document).on('click', '.EliminarEncargado', function(e){
 e.preventDefault();
var registro=$(this).parents("tr").find("td").eq(2).html();
registro= registro+' Usuario:'+$(this).parents("tr").find("td").eq(3).html();
   registro=registro+' Rol:'+$(this).parents("tr").find("td").eq(4).html();
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
                               EliminarEncargado($(this).parents("tr").find("td").eq(1).html());    
                            }
                       })
});
</script>

<script type="text/javascript">
// selecciona un registro para dar de BAJA el usuario
  $(document).on('click', '.BajaEncargado', function(e){
 e.preventDefault();
var registro=$(this).parents("tr").find("td").eq(2).html();
registro= registro+' / Usuario: '+$(this).parents("tr").find("td").eq(3).html();
   registro=registro+' / Rol:'+$(this).parents("tr").find("td").eq(4).html();
    Swal.fire({
      title: 'Está seguro de Registrar la BAJA de Usuario?',
      text: registro,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Dar de BAJA!'
    }).then((result) => {
                            if (result.isConfirmed) {
                               BajaEncargado($(this).parents("tr").find("td").eq(1).html());    
                            }
                       })
});
</script>
