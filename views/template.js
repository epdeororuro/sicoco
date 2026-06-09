$(function () {
  // Validar si estamos en la vista de Inicio/Dashboard comprobando si existen los contenedores Canvas
  if ($('#barChart').length > 0 || $('#pieChart').length > 0 || $('#donutChart').length > 0) {
    $.ajax({
      url: base_url + 'dashboard/get_data',
      type: 'GET',
      dataType: 'json',
      success: function(resp) {
        if (resp.status === 'success') {
          
          // 1. GRÁFICO DE BARRAS (Ingresos Mensuales en Guindo)
          if ($('#barChart').length > 0) {
            var barChartCanvas = $('#barChart').get(0).getContext('2d');
            var barChartData = {
              labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
              datasets: [{
                label               : 'Ingresos Reales (Bs.)',
                backgroundColor     : '#900C3F', // Color Institucional Guindo
                borderColor         : '#6C092F',
                borderWidth         : 1,
                data                : resp.ingresos
              }]
            };
            var barChartOptions = {
              responsive: true, maintainAspectRatio: false, datasetFill: false, legend: { display: true }, scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
            };
            new Chart(barChartCanvas, { type: 'bar', data: barChartData, options: barChartOptions });
          }

          // 2. GRÁFICO DE PASTEL/DONA (Ocupación del Catálogo)
          var pieElem = $('#pieChart').length > 0 ? $('#pieChart') : ($('#donutChart').length > 0 ? $('#donutChart') : null);
          if (pieElem) {
            var pieChartCanvas = pieElem.get(0).getContext('2d');
            var pieData = { labels: resp.espacios.labels, datasets: [{ data: resp.espacios.data, backgroundColor: resp.espacios.colores }] };
            var pieOptions = { maintainAspectRatio : false, responsive : true };
            new Chart(pieChartCanvas, { type: pieElem.attr('id') === 'donutChart' ? 'doughnut' : 'pie', data: pieData, options: pieOptions });
          }
        }
      }
    });
  }
});

// --- TEMPORIZADOR DE SESIÓN ANTI-CONGELAMIENTO (45 MINUTOS) ---
var tiempoLimiteMinutos = 45; 
var expiracionSesion = Date.now() + (tiempoLimiteMinutos * 60 * 1000);
var isUserActive = true;
var timerInactividad = setTimeout(function() { isUserActive = false; }, 3000);

var sessionTicker = setInterval(function() {
  var ahora = Date.now();
  var tiempoRestante = expiracionSesion - ahora;

  var $timerSpan = $('#session_timer');
  if ($timerSpan.length) {
    var $timerParent = $timerSpan.parent(); // Seleccionamos el contenedor <small>
    
    if (isUserActive) {
      $timerParent.removeClass('text-warning text-danger').addClass('text-success');
      $timerParent.html('<span id="session_timer"><i class="fas fa-wifi"></i> En Línea</span>');
    } else {
      var minutos = Math.floor((tiempoRestante % (1000 * 60 * 60)) / (1000 * 60));
      var segundos = Math.floor((tiempoRestante % (1000 * 60)) / 1000);
      var timeStr = (minutos < 10 ? "0" : "") + minutos + ":" + (segundos < 10 ? "0" : "") + segundos;
      
      var colorClass = (minutos < 5) ? 'text-danger' : 'text-warning'; // Rojo si quedan menos de 5 min
      $timerParent.removeClass('text-success text-warning text-danger').addClass(colorClass);
      $timerParent.html('<i class="fas fa-stopwatch"></i> Expira en: <span id="session_timer">' + timeStr + '</span>');
    }
  }

  if (tiempoRestante <= 0) {
    clearInterval(sessionTicker);
    Swal.fire({
        title: '¡Sesión Expirada!', text: 'Tu sesión ha finalizado por inactividad de ' + tiempoLimiteMinutos + ' minutos.', icon: 'warning',
        allowOutsideClick: false, allowEscapeKey: false, confirmButtonColor: '#d33', confirmButtonText: '<i class="fas fa-sign-in-alt"></i> Volver a Iniciar Sesión'
    }).then(() => { window.location.href = base_url + 'login/logout'; });
  }
}, 1000);

// Reiniciar tiempo al interactuar con el sistema
$(document).on('mousemove keypress click scroll', function() {
  expiracionSesion = Date.now() + (tiempoLimiteMinutos * 60 * 1000);
  isUserActive = true;
  
  clearTimeout(timerInactividad);
  timerInactividad = setTimeout(function() {
    isUserActive = false;
  }, 3000); // Espera 3 segundos sin movimiento para volver a mostrar el conteo
});

// --- AUTO-EXPANDIR Y MARCAR ACTIVO EL MENÚ ---
$(document).ready(function() {
  var currentUrl = window.location.href.split('?')[0];
  $('.nav-sidebar a').each(function() {
    if (this.href !== '' && this.href !== '#' && (this.href === currentUrl || currentUrl.startsWith(this.href + '/'))) {
      $(this).addClass('active');
      $(this).parents('.nav-treeview').prev('.nav-link').addClass('active');
      $(this).parents('.nav-item').addClass('menu-open');
    }
  });
});

// --- LÓGICA PARA CAMBIAR CONTRASEÑA GLOBAL ---
$(document).on('submit', '#FormCambiarClave', function(e) {
  e.preventDefault();
  $.ajax({
    url: base_url + 'usuario/cambiar_clave', type: 'POST', data: $(this).serialize(), dataType: 'json',
    success: function(resp) {
      if(resp.status === 'success') {
        $('#ModalCambiarClave').modal('hide');
        $('#FormCambiarClave')[0].reset();
        Swal.fire('¡Actualizada!', resp.message, 'success');
      } else { Swal.fire('Error', resp.message, 'error'); }
    },
    error: function() { Swal.fire('Error', 'Problema de conexión al servidor.', 'error'); }
  });
});