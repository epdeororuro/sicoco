<?php namespace Views;

  // Detectar si es una petición AJAX para no imprimir el diseño HTML
  $es_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
  if (!$es_ajax) {
      $template = new Template();
  }

  class Template{

    public function __construct(){
      global $controlador_actual;
      $mostrar_menu = ($controlador_actual !== 'login');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SICOCO - Empresa Pública Departamental de Oruro</title>
  <link rel="icon" href="img/logos/favicon.ico" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo URL; ?>views/template/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo URL; ?>views/template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo URL; ?>views/template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo URL; ?>views/template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- componente select2 -->
  <link rel="stylesheet" href="<?php echo URL; ?>views/template/plugins/select2/css/select2.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo URL; ?>views/template/dist/css/adminlte.min.css">
  <script src="<?php echo URL; ?>views/template/plugins/jquery/jquery-3.6.0.min.js"></script>
  

</head>

<style>
  /* --- Tema Guindo / Rojo Carmesí EPDEOR --- */
  .bg-guindo { background-color: #900C3F !important; color: white !important; }
  .text-guindo { color: #900C3F !important; }
  .btn-guindo { background-color: #900C3F; border-color: #900C3F; color: white; transition: all 0.3s ease; }
  .btn-guindo:hover { background-color: #6C092F; border-color: #6C092F; color: white; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0,0,0,0.2); }
  .card-outline.card-guindo { border-top: 3px solid #900C3F; }
  
  /* Fondo elegante para el login */
  .login-page {
      background: #f4f6f9;
      background: linear-gradient(135deg, #f4f6f9 50%, #d8c3c9 100%);
  }
</style>

<?php if($mostrar_menu): ?>
<body class="hold-transition sidebar-mini">
<div class="wrapper" >
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo URL; ?>"  class="nav-link">Inicio</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" title="Opciones de Cuenta">
          <i class="far fa-user-circle fa-lg"></i> Mi Cuenta
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow-sm">
          <span class="dropdown-item dropdown-header bg-light">Opciones de Usuario</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item" data-toggle="modal" data-target="#ModalCambiarClave">
            <i class="fas fa-key mr-2 text-primary"></i> Cambiar Contraseña
          </a>
          <div class="dropdown-divider"></div>
          <a href="<?php echo URL; ?>login/logout" class="dropdown-item dropdown-footer text-danger font-weight-bold">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión / Cambiar Usuario
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo URL; ?>" class="brand-link">
      <img src="<?php echo URL; ?>img/logos/logo.png" alt="SICOCO Logo" class="brand-image elevation-3" style="opacity: .9">
      <span class="brand-text font-weight-bold">SICOCO</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 text-center">
        <?php 
          $sessionController = new \Config\sessionController();
          $currentUser = $sessionController->getCurrentUser();
          $nombreMostrar = 'Personal';
          if(isset($currentUser['idmiembro'])) {
              $db = new \Models\Conexion();
              $stmt = $db->conexion->prepare("SELECT NOMBRE FROM usuarios WHERE IDUSUARIO = ?");
              $stmt->execute([$currentUser['idmiembro']]);
              $u = $stmt->fetch(\PDO::FETCH_ASSOC);
              if($u) $nombreMostrar = $u['NOMBRE'];
          }
        ?>
        <span class="d-block text-white" style="font-size: 0.85rem;"><i class="fas fa-user-tie"></i> <?php echo strtoupper($nombreMostrar); ?></span>
        <small class="text-warning font-weight-bold" style="font-size: 0.75rem;"><span id="session_timer"></span></small>
      </div>

     

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-columns"></i>
              <p>
                ACCESOS
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">

                <a class="nav-link" href="<?php echo URL; ?>usuario">
                  <i class="fa fa-users fa-lg" aria-hidden="true"></i>
                      <span>Usuarios</span></a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                CATÁLOGO
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL; ?>area">
                  <i class="fa fa-th fa-lg"></i>
                  <p>Áreas|Ubicaciones</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL; ?>catalogo">
                  <i class="fa fa-microchip fa-lg"></i>
                  <p>Espacios|Servicios</p>
                </a>
              </li>                           
            </ul>
          </li>
       
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                REGISTROS
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right"></span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a class="nav-link" href="<?php echo URL; ?>propuesta">
                  <i class="fas fa-hand-holding-usd fa-lg"></i>
                  <p>Garantías Propuestas</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL; ?>cumplimiento">
                  <i class="fas fa-shield-alt fa-lg"></i>
                  <p>Garantías Cumplimiento</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL; ?>contrato">
                  <i class="fa fa-money-check-alt fa-lg"></i>
                  <p>Contratos</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL; ?>cliente">
                  <i class="fa fa-address-card fa-lg"></i>
                  <p>Clientes</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL; ?>pagos">
                  <i class="fa fa-credit-card fa-lg"></i>
                  <p>Pagos</p>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL; ?>historial">
                  <i class="fa fa-cubes fa-lg"></i>
                  <p>Historial de Pagos</p>
                </a>
              </li>
            </ul>
          </li>
                   
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                REPORTES
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo URL; ?>reportes" class="nav-link">
                  <i class="fas fa-file-invoice-dollar fa-lg"></i>
                  <p>Movimientos de Ingreso</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fa fa-truck fa-lg"></i>
                  <p>Movimiento de Salida</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            
              <a class="nav-link" href="<?php echo URL; ?>login/logout">
                
              <i class="fa fa-power-off fa-lg"></i>
              <p>
                Cerrar Sesión
              </p>
            </a>
          </li>
        
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="font-size: 12px;">  
    <!-- Content Header (Page header) -->

<?php else: ?>
<body class="hold-transition login-page">
<?php endif; ?>
    <?php

 }

   public function __destruct(){
      global $controlador_actual;
      $mostrar_menu = ($controlador_actual !== 'login');
?>   

<?php if($mostrar_menu): ?>
  <!-- /.content-wrapper -->

  </div>
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.1.0
    </div>
    <center>
    <strong>Copyright &copy; 2024 <a href="https://adminlte.io">Ingeniería a Tu Medida</a>.</strong> Todos los Derechos Reservados.
    </center>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<?php endif; ?>

<!-- Modal Cambiar Contraseña Global -->
<div class="modal fade" id="ModalCambiarClave" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-guindo">
        <h5 class="modal-title"><i class="fas fa-key"></i> Cambiar Mi Contraseña</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormCambiarClave">
        <div class="modal-body">
          <div class="form-group">
            <label for="txt_nueva_clave">Nueva Contraseña</label>
            <input type="password" class="form-control shadow-sm" id="txt_nueva_clave" name="txt_nueva_clave" required minlength="4" placeholder="Ingrese su nueva contraseña">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="<?php echo URL; ?>views/template/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script> const base_url='<?php echo URL;?>';</script>
<script src="<?php echo URL; ?>views/template/global/funciones.js"></script>

<!-- Bootstrap 4 -->
<script src="<?php echo URL; ?>views/template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/select2/js/select2.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo URL; ?>views/template/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo URL; ?>views/template/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo URL; ?>views/template/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo URL; ?>views/template/dist/js/demo.js"></script>

<script src="<?php echo URL; ?>views/template/plugins/chart.js/Chart.min.js"></script>
<!-- Page specific script -->

<script>
  // --- CONFIGURACIÓN GLOBAL PARA DATATABLES ---
  // Esto aplicará automáticamente los 4 botones (Copy, Excel, PDF, Print) y el idioma Español a TODAS las tablas del sistema.
  $.extend( true, $.fn.dataTable.defaults, {
    "responsive": true,
    "autoWidth": false,
    "dom": "<'row mb-2'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
           "<'row'<'col-sm-12'tr>>" +
           "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    "buttons": [
      { extend: 'copy', text: '<i class="fas fa-copy"></i> Copiar', className: 'btn btn-secondary btn-sm shadow-sm' },
      { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-success btn-sm shadow-sm' },
      { extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn btn-danger btn-sm shadow-sm' },
      { extend: 'print', text: '<i class="fas fa-print"></i> Imprimir', className: 'btn btn-info btn-sm shadow-sm' }
    ],
    "language": {
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sSearch":         "Buscar:",
      "oPaginate": { "sFirst": "Primero", "sLast": "Último", "sNext": "Siguiente", "sPrevious": "Anterior" }
    }
  });
</script>

<!-- Script de la plantilla (Dashboard, Reloj, Menú, Clave) -->
<script src="<?php echo URL; ?>views/template.js"></script>
</script>


</body>
</html>


<?php
  }

  }

?>
