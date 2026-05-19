
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-users"></i> 
             <strong>Abastecimiento/Proveedores de Mercancias</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-primary btn-lm" data-toggle="modal" data-target="#ModalProveedores">
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

                <table id="TablaProveedores"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Razon social</th>
                    <th>Dirección</th>
                    <th>Teléfonos</th>
                    <th>Correo</th> 
                    <th>Acciones</th>                   
                  </tr>
                </thead>
                 
                  <tfoot class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Razon social</th>
                    <th>Dirección</th>
                    <th>Teléfonos</th>
                    <th>Correo</th> 
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
        <div id="ModalProveedores" class="modal fade" role="dialog">

          <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titulo">Registro de Proveedor de Mercancias</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
             <div class="modal-body">
              <p class="statusMsg"></p>
               <form role="form" enctype="multipart/form-data" id="FormProveedor"  method="POST" autocomplete="off">
                            <div class="form-group">
                              <input  type="hidden"  id="txt_idpersona" name="txt_idpersona"/>
                            </div>

                          <div class="row table-info"><i class="fa fa-address-card fa-2x"></i>
                            <h4>Representante Legal</h4>
                           </div>
                          
                          <div class="row">
                            <div class="col-sm-6">
                                <label for="txt_nombres">Nombres</label>
                                <input type="text" class="form-control" id="txt_nombres" name="txt_nombres" required  minlength="5" maxlength="20" placeholder="escriba los Nombres"  />
                            </div>

                            <div class="col-sm-6">
                                <label for="txt_apellidos">Apellidos</label>
                                <input type="text" class="form-control" id="txt_apellidos" name="txt_apellidos" 
                                required  minlength="5" maxlength="20" placeholder="Escriba los Apellidos"/>
                            </div>
                          </div>  
                          <div class="form-group">
                                <label for="txt_razon_social">Razón Social</label>
                                <input type="text" class="form-control" id="txt_razon_social" name="txt_razon_social" 
                                required  minlength="5" maxlength="100" placeholder="Ingrese la razón social del proveedor"/>
                            </div>
                         
                         <div class="row table-info"><i class="fa fa-globe fa-2x"></i>
                            <h4>Ubicación y Contactos</h4>
                           </div>

                         <div class="row">
                            <div class="col-sm-6">
                                <label for="txt_telefonos">Números de Contacto</label>
                                <input type="text" class="form-control" id="txt_telefonos" name="txt_telefonos" 
                                required  minlength="5" maxlength="20" placeholder="Nro Contacto 1 - Nro Contacto 2"/>
                            </div>

                            <div class="col-sm-6">
                                <label for="txt_correo">Correo Electrónico</label>
                                <input type="email" class="form-control" id="txt_correo" name="txt_correo" 
                                required  minlength="5" maxlength="20" placeholder="cuenta_correo@dominio.com"/>
                            </div>
                          </div>
                         
                         <div class="form-group">
                                <label for="txt_direccion">Direccion de Proveedor</label>
                                <input type="text" class="form-control" id="txt_direccion" name="txt_direccion" 
                                required  minlength="5" maxlength="100" placeholder="País, Estado/Ciudad, dirección"/>
                            </div>
                                              
                    </form>
              </div>
              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_InsertProveedor" name="btn_InsertProveedor" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Registrar                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_EditarProveedor" name="btn_EditarProveedor" data-dismiss="modal">
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
    $("#btn_InsertProveedor").on('click', function(e){
      e.preventDefault();
       Insert_Proveedores();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_EditarProveedor").on('click', function(e){
      e.preventDefault();
       Editar_Proveedores();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btnNuevoRegistro").on('click', function(e){
      e.preventDefault();
       $("#titulo").html("Registro de Proveedor de Mercancias");
       $("#OpcionEditar").hide();
       $("#OpcionNuevo").show("slow");
       LimpiarCamposProveedor();
    });
  });
</script>

<script type="text/javascript">
   $(document).ready(function(){
    ListarProveedores();  
   });
</script>

<script type="text/javascript">
// selecciona un registro para editar
  $(document).on('click', '.EditarProveedores', function(e){
 e.preventDefault();

 $("#titulo").html("Modificar Registro");
 $("#OpcionNuevo").hide();
 $("#OpcionEditar").show("slow");

 document.getElementsByName("txt_idpersona")[0].value=$(this).parents("tr").find("td").eq(0).html();
 document.getElementsByName("txt_nombres")[0].value=$(this).parents("tr").find("td").eq(2).html();
 document.getElementsByName("txt_apellidos")[0].value=$(this).parents("tr").find("td").eq(1).html();
 document.getElementsByName("txt_razon_social")[0].value=$(this).parents("tr").find("td").eq(3).html();
 document.getElementsByName("txt_telefonos")[0].value=$(this).parents("tr").find("td").eq(5).html();
 document.getElementsByName("txt_correo")[0].value=$(this).parents("tr").find("td").eq(6).html();
 document.getElementsByName("txt_direccion")[0].value=$(this).parents("tr").find("td").eq(4).html();

});
</script>

<script type="text/javascript">
// selecciona un registro para eliminar
  $(document).on('click', '.EliminarProveedores', function(e){
 e.preventDefault();
 var registro=$(this).parents("tr").find("td").eq(2).html()+' '+$(this).parents("tr").find("td").eq(1).html();
   registro=registro+' Razón Social: '+$(this).parents("tr").find("td").eq(3).html();
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
                               EliminarProveedores($(this).parents("tr").find("td").eq(0).html());    
                            }
                       })
});
</script>
