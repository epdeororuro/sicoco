
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-users"></i> 
             <strong>Catálogo/Servicios</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-guindo font-weight-bold shadow-sm" data-toggle="modal" data-target="#ModalCatalogo">
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

                <table id="TablaCatalogo"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class="bg-guindo">
                  <tr>
                    <th>Nro</th>
                    <th>Ubicación</th>
                    <th>Descripción</th>
                    <th>Alquiler[BS]</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                  
                  <tfoot class="bg-guindo">
                  <tr>
                    <th>Nro</th>
                    <th>Ubicación</th>
                    <th>Descripción</th>
                    <th>Alquiler[BS]</th>
                    <th>Estado</th>
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
        <div id="ModalCatalogo" class="modal fade" role="dialog">

          <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header bg-guindo text-white" id="header_modal_catalogo">
                <h4 class="modal-title" id="titulo">Registro de Servicios</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
              </div>
              
              <div class="modal-body">
                <p class="statusMsg"></p>
                 <form role="form" enctype="multipart/form-data" 
                 id="FormCatalogo"  method="POST" autocomplete="off">
                      <div class="form-group">
                        <input  type="hidden" id="txt_idcatalogo" 
                         name="txt_idcatalogo">
                      </div>

                    <div class="row">
                       <div class="col-sm-8" >
                              <label for="SelBuscarArea">Categoría de artículo</label>
                              <select id="SelBuscarArea" 
                                name="SelBuscarArea" class="form-control" style="width:100%">
                                 <option value="0">Seleccionar..</option>
                             </select>                              
                        </div>
                    
                    </div>                        
                    
                         
                    <div class="row">
                      <div class="col-sm-8">
                        <label for="txt_descripcion">Descripción</label>
                        <input type="text" class="form-control" id="txt_descripcion" name="txt_descripcion"     required  minlength="5" maxlength="50" placeholder="Escriba la Descripcion del espacio"/>
                      </div>

                      <div class="col-sm-4">
                        <label for="txt_alquiler">Alquiler</label>
                        <input type="number" step="0.1" class="form-control" id="txt_alquiler" name="txt_alquiler" required value="0" />
                      </div>
                    </div>                               
                </form>
              </div>
              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" id="btn_InsertCatalogo" name="btn_InsertCatalogo">
                  <span><i class="fas fa-save"></i></span>
                  Registrar
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_EditarCatalogo" name="btn_EditarCatalogo">
                  <span><i class="fas fa-save"></i></span>
                  Guardar Cambios                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>


            </div>
          </div>
        </div>  <!--FIN Modal -->

<script src="<?php echo URL; ?>views/catalogo/catalogo.js"></script>
