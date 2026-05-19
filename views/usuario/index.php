
  <section class="content-header">
      <div class="container-fluid">
        <input type="hidden" id="user_rol_sesion" value="<?php echo isset($_SESSION['cargo']) ? $_SESSION['cargo'] : ''; ?>">
        <input type="hidden" id="id_usuario_sesion" value="<?php echo isset($_SESSION['idmiembro']) ? $_SESSION['idmiembro'] : ''; ?>">
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
                    <th>Nro</th>
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
                    <th>Nro</th>
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

          <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header bg-primary text-white" id="header_modal_usuario">
                <h4 class="modal-title" id="titulo">Registro de Accesos</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
              <div class="modal-body">
                <p class="statusMsg"></p>
                 <form role="form" enctype="multipart/form-data" id="FormUsuario"  method="POST" autocomplete="off">
                            <div class="form-group">
                              <input  type="hidden"  id="txt_idusuario" name="txt_idusuario">
                            </div>                
                          <div class="row">
                            <div class="col-sm-12">
                                <label for="txt_nombre">Nombre Completo</label>
                                <input type="text" class="form-control" id="txt_nombre" name="txt_nombre" required  minlength="5" maxlength="50" placeholder="Escriba el nombre completo"/>
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
                                <select class="form-control" id="txt_idrol" name="txt_idrol" required>
                                  <option value="">Cargando roles...</option>
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

<script src="<?php echo URL; ?>views/usuario/usuario.js"></script>