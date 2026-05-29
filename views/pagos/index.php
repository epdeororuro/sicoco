
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <i class="fas fa-cash-register"></i> 
             <strong>Módulo de Caja - Historial de Transacciones</strong>
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
              <div class="card-header">
                <h3 class="card-title">Listado de todos los pagos registrados en el sistema</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="TablaHistorial" class="table table-bordered table-striped table-hover table-sm" style="width:100%">
                  <thead class="table-success">
                  <tr>
                    <th>Nro. Recibo</th>
                    <th>Fecha y Hora</th>
                    <th>Cliente</th>
                    <th>Contrato</th>
                    <th>Periodos Cobrados</th>
                    <th>Total (Bs.)</th>
                    <th>Cajero</th>
                    <th>Acción</th>
                  </tr>
                  </thead>
                  <tbody>
                    <!-- El contenido se cargará por Ajax -->
                  </tbody>
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

<script src="<?php echo URL; ?>views/pagos/pagos.js"></script>

<script type="text/javascript">
   $(document).ready(function(){
    // Al cargar la página, se inicializa la tabla del historial de caja.
    ListarHistorialCaja();
   });
</script>
