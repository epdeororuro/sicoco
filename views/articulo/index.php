
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fa fa-cube"></i> 
             <strong>Catálogo/Artículos</strong>
            
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <span class="float-right"> 
               <button type="button" id="btnNuevoRegistro" class="btn btn-primary btn-lm" data-toggle="modal" data-target="#ModalArticulo">
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

                <table id="TablaArticulo"  class="table table-bordered table-striped table-hover table-sm">
                 <thead class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>Categoría</th>
                    <th>Artículo</th>
                    <th>Stock Mínimo</th>
                    <th>Código de Barra</th>                    
                    <th>Acciones</th>                   
                  </tr>
                </thead>
                 
                  <tfoot class=" table-success">
                  <tr>
                    <th>Id</th>
                    <th>Categoría</th>
                    <th>Artículo</th>
                    <th>Stock Mínimo</th>
                    <th>Código de Barra</th>                    
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
        <div id="ModalArticulo" class="modal fade" role="dialog">

          <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titulo">Registro de Artículos</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
              <div class="modal-body">
                <p class="statusMsg"></p>
               <form role="form" enctype="multipart/form-data" id="FormArticulo"  method="POST" autocomplete="off">
                            <div class="form-group">
                              <input  type="hidden"  id="txt_idarticulo" name="txt_idarticulo" value="0" />
                            </div>            

                          <div class="row table-info"> <h4>Datos Generales</h4> </div>
                             <div class="row" id="panel1">
                               <div class="col-sm-12">
                                <label for="txt_descripcion">Descripción del Artículo</label>
                                <input type="text" class="form-control" id="txt_descripcion" name="txt_descripcion" required  minlength="5" maxlength="250" placeholder="descripcion y caracteriscticas del artículo"  />
                               </div>
                             </div>

                           <div class="row" id="panel2">
                              <div class="col-sm-8">
                                  <label for="txt_codigo_barra">Código de Barra del Artículo</label>
                                  <input type="text" class="form-control" id="txt_codigo_barra" name="txt_codigo_barra" required  minlength="2" maxlength="30" placeholder="Código de barra del Artículo" value="00000" />
                              </div>

                              <div class="col-sm-4">
                                  <label for="txt_stock">Stock Mínimo</label>
                                  <input type="number" class="form-control" id="txt_stock" name="txt_stock" required  minlength="1" maxlength="3" value="10"  />
                              </div>                                       
                          </div>

              

                <div class="container-fluid" id="panel2">
                          <div class="row table-info"> <h4>Categoría de Artículo</h4> </div>
                          <div class="row">
                              <div class="col-sm-12">
                                <input type="text" class="form-control" id="txt_categoria" name="txt_categoria" disabled />
                              </div>
                          </div>

                            <div class="col-sm-12" id="DivComboBox">
                              <label for="SelBuscarCategoria">Categoría de artículo</label>
                              <select id="SelBuscarCategoria" name="SelBuscarCategoria" class="form-control" style="width:100%">
                                 <option value="0">Seleccionar una Categoría de Artículo</option>
                             </select>                                                              
                            </div> 
              </div>


                    </form>
              </div>
              <div class="modal-footer" id="OpcionNuevo">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="BtnInsertArticulo" name="BtnInsertArticulo" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Registrar                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionEditar">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="BtnEditArticulo" name="BtnEditArticulo" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Guardar Cambios                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Salir</button>
              </div>

              <div class="modal-footer" id="OpcionCambioCategoria">
                <button type="button" class="btn btn-success btn-lg submitBtn" 
                  id="BtnCambiaCategoria" name="BtnCambiaCategoria" data-dismiss="modal">
                  <span><i class="fas fa-save"></i></span>
                  Cambiar Categoría                   
                </button>
                
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Cancelar</button>
              </div>

            </div>
          </div>
        </div>  <!--FIN Modal -->


<script type="text/javascript">
  $(document).ready(function(){
    $("#BtnInsertArticulo").on('click', function(e){
      e.preventDefault();
       //InsertEncargados();
       CrudArticulo('articulo/add');

    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btnNuevoRegistro").on('click', function(e){
      e.preventDefault();
       $("#titulo").html("Registro de Artículos");
       $("#OpcionEditar").hide();
       $("#OpcionCambioCategoria").hide();
       $("#OpcionNuevo").show("slow");
      // $("#DivComboBox").show("slow");
      BloquearDatos(0);
      
       //limpiar los campos de ingreso de datos
       $('#SelBuscarCategoria').val('0').trigger('change');
       document.getElementsByName("txt_descripcion")[0].value="";
       document.getElementsByName("txt_stock")[0].value="10";
       document.getElementsByName("txt_codigo_barra")[0].value="00000";   
       document.getElementsByName("txt_categoria")[0].value="";  
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#BtnEditArticulo").on('click', function(e){
      e.preventDefault();
       //InsertEncargados();
       CrudArticulo('articulo/edit');
    });
  });
</script>

<script type="text/javascript">
// selecciona un registro para editar
  $(document).on('click', '.EditarArticulo', function(e){
 e.preventDefault();

 $("#titulo").html("Modificar Registro");
 $("#OpcionNuevo").hide();
 $("#OpcionCambioCategoria").hide();
 $("#OpcionEditar").show("slow");

document.getElementsByName("txt_idarticulo")[0].value = $(this).parents("tr").find("td").eq(0).html();
document.getElementsByName("txt_descripcion")[0].value = $(this).parents("tr").find("td").eq(2).html();
document.getElementsByName("txt_stock")[0].value=$(this).parents("tr").find("td").eq(3).html();
document.getElementsByName("txt_codigo_barra")[0].value = $(this).parents("tr").find("td").eq(4).html();
document.getElementById("txt_categoria").value=$(this).parents("tr").find("td").eq(1).html();

BloquearDatos(1);
});
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#BtnCambiaCategoria").on('click', function(e){
      e.preventDefault();

    Swal.fire({
      title: 'Está seguro de Cambiar la Categoría de este Registro?',
      text: '',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Cambiar Categoría!'
    }).then((result) => {
                        if (result.isConfirmed) {
                              CrudArticulo('articulo/cambio');
                         }
                       })
});

      
    });
  
</script>

<script type="text/javascript">
// selecciona un registro para cambiar la categoria
  $(document).on('click', '.CambioCategoria', function(e){
 e.preventDefault();

 $("#titulo").html("Cambiar Categoría");
 $("#OpcionNuevo").hide();
 $("#OpcionEditar").hide();
 $("#OpcionCambioCategoria").show("slow");

document.getElementsByName("txt_idarticulo")[0].value = $(this).parents("tr").find("td").eq(0).html();
document.getElementsByName("txt_descripcion")[0].value = $(this).parents("tr").find("td").eq(2).html();
document.getElementsByName("txt_stock")[0].value=$(this).parents("tr").find("td").eq(3).html();
document.getElementsByName("txt_codigo_barra")[0].value = $(this).parents("tr").find("td").eq(4).html();
document.getElementById("txt_categoria").value=$(this).parents("tr").find("td").eq(1).html();

BloquearDatos(0);

});
</script>

<script type="text/javascript">
   $(document).ready(function(){
    ListarArticulo();  
     $("#SelBuscarCategoria").select2(LlenarBuscadorCategoria(1));  
    BloquearDatos(0);
   });
   
</script>


<script type="text/javascript">
// selecciona un registro para eliminar
  $(document).on('click', '.EliminarArticulo', function(e){
 e.preventDefault();
var registro=$(this).parents("tr").find("td").eq(2).html();

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
                               //EliminarEncargado($(this).parents("tr").find("td").eq(1).html());  
                               CrudArticulo('articulo/delete/'+$(this).parents("tr").find("td").eq(0).html());  
                            }
                       })
});
</script>

