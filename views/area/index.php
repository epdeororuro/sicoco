
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-users"></i> 
             <strong>Catálogo/Área</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-guindo font-weight-bold shadow-sm" data-toggle="modal" data-target="#ModalArea">
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
            <div class="card card-outline card-guindo">
             
              <!-- /.cargar el listado de la tabla -->
              <div class="card-body" id="listado">

                <table id="TablaArea"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class="bg-guindo">
                  <tr>
                    <th>Nro</th>
                    <th>Referencia</th>
                    <th>Ubicación</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                 
                  <tfoot class="bg-guindo">
                  <tr>
                    <th>Nro</th>
                    <th>Referencia</th>
                    <th>Ubicación</th>
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
        <div id="ModalArea" class="modal fade" role="dialog">

          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header bg-guindo text-white" id="header_modal_area">
                <h4 class="modal-title" id="titulo">Registro de Áreas y Ubicación</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
              </div>
              
              <div class="modal-body">
                <p class="statusMsg"></p>
                 <form role="form" enctype="multipart/form-data" 
                 id="FormArea"  method="POST" autocomplete="off">
                            <div class="form-group">
                              <input  type="hidden"  id="txt_idarea" name="txt_idarea">
                            </div>
                            

                          <div class="row">
                            <div class="col-sm-12">
                                <label for="txt_referencia">Referencia</label>
                                <input autocomplete="on" type="text" class="form-control" id="txt_referencia" name="txt_referencia" required  minlength="5" maxlength="50" placeholder="Escriba la referencia"  />
                            </div>                           
                          </div>  
                         
                         <div class="row">
                            <div class="col-sm-12">
                              <label for="txt_ubicacion">Ubicación</label>
                              <input type="text" class="form-control" id="txt_ubicacion" name="txt_ubicacion" 
                              required  minlength="5" maxlength="50" placeholder="Escriba la ubicación"/>
                            </div>                            
                         </div>                               
                    </form>
              </div>
              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" id="btn_InsertArea" name="btn_InsertArea">
                  <span><i class="fas fa-save"></i></span>
                  Registrar
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_EditarArea" name="btn_EditarArea">
                  <span><i class="fas fa-save"></i></span>
                  Guardar Cambios                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>


            </div>
          </div>
        </div>  <!--FIN Modal -->

<script src="<?php echo URL; ?>views/area/area.js"></script>
