
  <section class="content-header">
      <div class="container-fluid" id="Encabezado1">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fa fa-th fa-lg"></i> 
             <strong>Catálogo/Categoría de Ensambles</strong>            
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

 <div class="container-fluid" id="Encabezado2">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fa fa-th fa-lg"></i> 
             <strong>Catálogo/Componentes de Ensambles</strong>            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoComponente" class="btn btn-primary btn-lm" data-toggle="modal" data-target="#ModalComponente">
                <i class="fas fa-plus-circle"></i> 
                 Nuevo Componente
               </button>
            </span>
             <span class="float-right"> 
               <button type="button" id="btnVolver" class="btn btn-warning btn-lm" 
                   onclick="location.reload();" >
                <i class="fas fa-arrow-circle-left "></i> 
                 Volver
               </button>
            </span>
            </ol>
          </div>
        </div>
        <div class="row mb-2 bg-gradient-info">
          <div class="row card-header col-12"> 
            <h5 class="form-group" id="TituloEnsamble"></h5>
            <input type="hidden" class="form-control" id="txt_IdCatEnsamble" name="txt_IdCatEnsamble"/>
            </div>          
        </div>
    </div><!-- /.container-fluid -->

  </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid" id="Cuerpo1">
        <div class="row">
          <div class="col-12">
            <div class="card">
             
              <!-- /.cargar el listado de la tabla -->
              <div class="card-body" >
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

      <div class="container-fluid" id="Cuerpo2">
        <div class="row">
          <div class="col-12">
            <div class="card">
             
              <!-- /.cargar el listado de la tabla -->
              <div class="card-body" >
                <table id="TablaComponente"  class="table table-bordered table-striped table-hover table-sm " style="width:100%">
                 <thead class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>Descripción</th>
                    <th>Precio Unit</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>                   
                  </tr>
                </thead>
                 
                <tfoot class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>Descripción</th>
                    <th>Precio Unit</th>
                    <th>Cantidad</th>
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
       CrudCategoria('catensamble/add');
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_editar_categoria").on('click', function(e){
      e.preventDefault();
      CrudCategoria('catensamble/edit');
    });
  });
</script>



<script type="text/javascript">
  $(document).ready(function(){
    $("#btnNuevoRegistro").on('click', function(e){
      e.preventDefault();
       $("#titulo").html("Registro de Categorías de Ensambles");
       $("#OpcionEditar").hide();
       $("#OpcionNuevo").show("slow");
       //limpiar los campos
       document.getElementsByName("txt_descripcion")[0].value = "";
    });
  });
</script>

<script type="text/javascript">
   $(document).ready(function(){   
ListarCategoriaEnsamble();  
   
   $("#Encabezado1").show("slow");
   $("#Encabezado2").hide();

   $("#Cuerpo1").show("slow");
   $("#Cuerpo2").hide();


   });
</script>

<script>
  $(document).on('click', '.ComponenteEnsamble', function(e){
 // funcion para mostrar el formulario de componentes de un ensamble
   e.preventDefault();

   $("#Encabezado2").show("slow");
   $("#Encabezado1").hide();
document.getElementsByName("txt_IdCatEnsamble")[0].value=$(this).parents("tr").find("td").eq(0).html();
   ListarComponenteEnsamble($("#txt_IdCatEnsamble").val());

   $("#Cuerpo2").show("slow");
   $("#Cuerpo1").hide();
   $("#TituloEnsamble").html($(this).parents("tr").find("td").eq(1).html());
   
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
                             CrudCategoria('catensamble/delete/'+$(this).parents("tr").find("td").eq(0).html());  
                            }
                       }) 
});
</script>

<script type="text/javascript">
// selecciona un registro para dar de Baja y quitarlo del grupo de categorias
  $(document).on('click', '.BajaCategoria', function(e){
 e.preventDefault();
 var reg=$(this).parents("tr").find("td").eq(1).html();
 var estado=$(this).parents("tr").find("td").eq(4).html();
     Swal.fire({
      title: 'Está seguro de Retirar esta Categoría de Ensambles?',
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