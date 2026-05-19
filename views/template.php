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
  <title>Empresa Pública Departamental de Oruro</title>

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
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contactos</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo URL; ?>" class="brand-link">
      <img src="<?php echo URL; ?>views/template/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Arriendos</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo URL; ?>views/template/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Usuario de sistema</a>
        </div>
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
                <span class="badge badge-info right"> 0 </span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL; ?>cliente">
                  <i class="fa fa-address-card fa-lg"></i>
                  <p>Clientes</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL; ?>contrato">
                  <i class="fa fa-money-check-alt fa-lg"></i>
                  <p>Contratos</p>
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
                <a href="../forms/general.html" class="nav-link">
                  <i class="fa fa-ship fa-lg"></i>
                  <p>Movimiento de Ingreso</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../forms/advanced.html" class="nav-link">
                  <i class="fa fa-truck fa-lg"></i>
                  <p>Movimiento de Salida</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../forms/editors.html" class="nav-link">
                  <i class="fa fa-cubes fa-lg"></i>
                  <p>Contenidos</p>
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

<!-- jQuery -->
<script src="<?php echo URL; ?>views/template/plugins/jquery/jquery-3.6.0.min.js"></script>
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
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

    var areaChartData = {
      labels  : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
      datasets: [
        {
          label               : 'Alquilados',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90]
        },
        {
          label               : 'Disponibles',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40]
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    new Chart(areaChartCanvas, {
      type: 'line',
      data: areaChartData,
      options: areaChartOptions
    })

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = $.extend(true, {}, areaChartOptions)
    var lineChartData = $.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: lineChartData,
      options: lineChartOptions
    })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Espacio',
          'Servicio',
          'area',
          'Ambiente',
          'Estacionamiento',
          'Público',
      ],
      datasets: [
        {
          data: [700,500,400,600,300,100],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = $.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  })
</script>


</body>
</html>


<?php
  }

  }

?>