
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fa fa-th fa-lg"></i> 
             <strong>Catálogo/Categoría de Artículos</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-primary btn-lm" data-toggle="modal" data-target="#ModalCategoria">
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

                <table id="TablaCategoria"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">
                  <tr>
                    <th>Nro</th>
                    <th>Descripción</th>
                    <th>Creación</th>
                    <th>Retiro</th>
                    <th>Vigente</th> 
                    <th>Acciones</th>                   
                  </tr>
                </thead>
                 
                  <tfoot class=" table-success">
                  <tr>
                    <th>Nro</th>
                    <th>Descripción</th>
                    <th>Creación</th>
                    <th>Retiro</th>
                    <th>Vigente</th> 
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
        <div id="ModalCategoria" class="modal fade" role="dialog">

          <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titulo">Registro de Categorías de Productos</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
              <div class="modal-body">
                <p class="statusMsg"></p>
                <form role="form" enctype="multipart/form-data" id="FormCategoria" method="POST" autocomplete="off">
                            <div class="form-group">
                              <input  type="hidden"  id="txt_idcategoria" name="txt_idcategoria"/>
                            </div>

                            <div class="form-group">
                                <label for="txt_descripcion">Descripción</label>
                                <input type="text" class="form-control" id="txt_descripcion" name="txt_descripcion" required  minlength="5" maxlength="80" placeholder="Ingrese la Descripcion"  />
                            </div>                   
                    </form>
              </div>
              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_insert_categoria" name="btn_insert_categoria" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Registrar                   
                </button>                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="btn_editar_categoria" name="btn_editar_categoria" data-dismiss="modal">
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
    $("#btn_insert_categoria").on('click', function(e){
      e.preventDefault();
       CrudCategoria('categoria/add');
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_editar_categoria").on('click', function(e){
      e.preventDefault();
       CrudCategoria('categoria/edit');
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btnNuevoRegistro").on('click', function(e){
      e.preventDefault();
       $("#titulo").html("Registro de Categorías de Productos");
       $("#OpcionEditar").hide();
       $("#OpcionNuevo").show("slow");
//limpiar los campos de texto
       document.getElementsByName("txt_descripcion")[0].value = "";
    });
  });
</script>

<script type="text/javascript">
   $(document).ready(function(){
    ListarCategoria();  
    $("#ComboArea").select2(Llenar_Areas());
   });
</script>

<script type="text/javascript">
// selecciona un registro para editar
  $(document).on('click', '.EditarCategoria', function(e){
 e.preventDefault();
 document.getElementsByName("txt_idcategoria")[0].value = $(this).parents("tr").find("td").eq(0).html();
 document.getElementsByName("txt_descripcion")[0].value = $(this).parents("tr").find("td").eq(1).html();
 $("#titulo").html("Modificar Registro");
 $("#OpcionNuevo").hide();
 $("#OpcionEditar").show("slow");
});
</script>

<script type="text/javascript">
// selecciona un registro para eliminar
  $(document).on('click', '.EliminarCategoria', function(e){
 e.preventDefault();
 var reg=$(this).parents("tr").find("td").eq(1).html();
     Swal.fire({
      title: 'Está seguro de Eliminar este Registro?',
      text: reg+" /Esta operación NO podrá Revertirse",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Eliminar el Registro!'
    }).then((result) => {
                            if (result.isConfirmed) {
                              CrudCategoria('categoria/delete/'+$(this).parents("tr").find("td").eq(0).html()); 
                            }
                       })
 });
</script>

<script type="text/javascript">
// selecciona un registro para dar de Baja y quitarlo del grupo de categorias
  $(document).on('click', '.BajaCategoria', function(e){
 e.preventDefault();
 var reg=$(this).parents("tr").find("td").eq(1).html();
     Swal.fire({
      title: 'Está seguro de Retirar esta Categoría?',
      text: reg+" /Esta operación NO podrá Revertirse",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Retirar la Categoria!'
    }).then((result) => {
                            if (result.isConfirmed) {
                               RetirarCategoria($(this).parents("tr").find("td").eq(0).html(), 'RETIRAR');    
                            }
                       })
});
</script>

<script type="text/javascript">
// selecciona un registro para Habilitar el registro de categorias
  $(document).on('click', '.HabilitaCategoria', function(e){
 e.preventDefault();
 var reg=$(this).parents("tr").find("td").eq(1).html();
     Swal.fire({
      title: 'Está seguro de Habilitar Nuevamente esta Categoría?',
      text: reg,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Habilitar la Categoria!'
    }).then((result) => {
                            if (result.isConfirmed) {
                               RetirarCategoria($(this).parents("tr").find("td").eq(0).html(),'HABILITAR');    
                            }
                       })
});
</script>