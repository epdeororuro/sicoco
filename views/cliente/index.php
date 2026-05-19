
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-users"></i> 
             <strong>Registros/Clientes</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-primary btn-lm" data-toggle="modal" data-target="#ModalCliente">
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

                <table id="TablaCliente"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Cédula</th>
                    <th>Contactos</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                 
                  <tfoot class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Cédula</th>
                    <th>Contactos</th>
                    <th>Dirección</th>
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
        <div id="ModalCliente" class="modal fade" role="dialog">

          <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titulo">Registro de Clientes</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
              <div class="modal-body">
                <p class="statusMsg"></p>
                 <form role="form" enctype="multipart/form-data" id="FormCliente"  method="POST" autocomplete="off">
                            <div class="form-group">
                              <input  type="hidden"  id="txt_idcliente" name="txt_idcliente">
                            </div>
                            

                          <div class="row">
                            <div class="col-sm-8">
                                <label for="txt_nombre">Nombre Completo</label>
                                <input type="text" class="form-control" id="txt_nombre" name="txt_nombre" required  minlength="5" maxlength="50" placeholder="escriba el Nombre Completo" >
                            </div>

                            <div class="col-sm-4">
                                <label for="txt_cedula">Cédula de Identidad</label>
                                <input type="number" class=" form-control" id="txt_cedula" 
                                name="txt_cedula" required >
                            </div>
                          </div>  
                         
                         <div class="row">
                            <div class="col-sm-4">
                                <label for="txt_contactos">Número de Contacto</label>
                                <input type="text" class="form-control" id="txt_contactos" name="txt_contactos" required  minlength="5" maxlength="20" placeholder="000000000-00000000">
                            </div>

                            <div class="col-sm-8">
                              <label for="txt_direccion">Dirección</label>
                              <input type="text" class="form-control" id="txt_direccion" 
                              name="txt_direccion" required  
                              minlength="5" maxlength="70" 
                              placeholder="Dirección..">
                            </div>                               
                    </form>
              </div>
              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_InsertCliente" name="btn_InsertCliente" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Registrar                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_EditarCliente" name="btn_EditarCliente" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Guardar Cambios                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>


            </div>
          </div>
        </div>  <!--FIN Modal -->

<script src="<?php echo URL; ?>views/cliente/cliente.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_InsertCliente").on('click', function(e){
      e.preventDefault();
       Insert_Cliente();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_EditarCliente").on('click', function(e){
      e.preventDefault();
       Editar_Cliente();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btnNuevoRegistro").on('click', function(e){
      e.preventDefault();
       $("#titulo").html("Registro de Clientes");
       $("#OpcionEditar").hide();
       $("#OpcionNuevo").show("slow");
       LimpiarCamposCliente();
    });
  });
</script>

<script type="text/javascript">
   $(document).ready(function(){
    ListarCliente();
   });
</script>

<script type="text/javascript">
// selecciona un registro para editar
  $(document).on('click', '.EditarCliente', function(e){
 e.preventDefault();

 $("#titulo").html("Modificar Registro");
 $("#OpcionNuevo").hide();
 $("#OpcionEditar").show("slow");

 document.getElementsByName("txt_idcliente")[0].value=$(this).parents("tr").find("td").eq(0).html();
 document.getElementsByName("txt_nombre")[0].value=$(this).parents("tr").find("td").eq(1).html();
 document.getElementsByName("txt_cedula")[0].value=$(this).parents("tr").find("td").eq(2).html();
 document.getElementsByName("txt_contactos")[0].value=$(this).parents("tr").find("td").eq(3).html();
 document.getElementsByName("txt_direccion")[0].value=$(this).parents("tr").find("td").eq(4).html();

});
</script>

<script type="text/javascript">
// selecciona un registro para eliminar
  $(document).on('click', '.EliminarCliente', function(e){
 e.preventDefault();
 var registro=$(this).parents("tr").find("td").eq(1).html()+' '+$(this).parents("tr").find("td").eq(2).html();
   //registro=registro+' cedula:'+$(this).parents("tr").find("td").eq(3).html();
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
                               EliminarCliente($(this).parents("tr").find("td").eq(0).html());    
                            }
                       })
});
</script>