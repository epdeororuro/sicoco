var chartIngresos = null;
var chartEspacios = null;

$(document).ready(function() {
    cargarKPIs();
    cargarGrafico();
    cargarGraficoEspacios();

    // Cerrar el modal automáticamente al pedir el reporte
    $('#formCierreCaja').on('submit', function() {
        $('#ModalCierreCaja').modal('hide');
    });
});

function cargarKPIs() {
    $.ajax({
        url: base_url + 'inicio/cargar_kpis',
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            if(resp.status === 'success') {
                // Animamos levemente los números
                $('#kpi_hoy').text('Bs. ' + parseFloat(resp.data.INGRESOS_HOY).toFixed(2));
                $('#kpi_mes').text('Bs. ' + parseFloat(resp.data.INGRESOS_MES).toFixed(2));
                $('#kpi_contratos').text(resp.data.CONTRATOS_VIGENTES);
                $('#kpi_espacios').text(resp.data.ESPACIOS_DISPONIBLES);
            }
        }
    });
}

function cargarGrafico() {
    $.ajax({
        url: base_url + 'inicio/cargar_grafico',
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            if(resp.status === 'success') {
                var meses = [];
                var totales = [];
                
                var nombresMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

                $(resp.data).each(function(i, v) {
                    var partes = v.mes.split('-');
                    var mesIndex = parseInt(partes[1]) - 1;
                    var nombreMes = nombresMeses[mesIndex] + ' ' + partes[0];
                    
                    meses.push(nombreMes);
                    totales.push(parseFloat(v.total));
                });
                
                dibujarChart(meses, totales);
            }
        }
    });
}

function dibujarChart(labels, data) {
    var ctx = document.getElementById('graficoIngresos').getContext('2d');
    
    if (chartIngresos) { chartIngresos.destroy(); }
    
    chartIngresos = new Chart(ctx, {
        type: 'bar', // Gráfico de barras
        data: {
            labels: labels,
            datasets: [{
                label: 'Ingresos Mensuales (Bs.)',
                backgroundColor: 'rgba(40, 167, 69, 0.85)', // Verde success
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1,
                borderRadius: 4,
                data: data
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Bs. ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return 'Bs. ' + value; }
                    }
                }
            }
        }
    });
}

function cargarGraficoEspacios() {
    $.ajax({
        url: base_url + 'inicio/cargar_grafico_espacios',
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            if(resp.status === 'success') {
                var labels = [];
                var data = [];
                var backgroundColors = [];
                
                // Inicializamos en 0 por si no hay datos de alguno
                var alquilados = 0;
                var disponibles = 0;

                $(resp.data).each(function(i, v) {
                    if(v.ESTADO === 'ALQUILADO') alquilados = parseInt(v.cantidad);
                    if(v.ESTADO === 'DISPONIBLE') disponibles = parseInt(v.cantidad);
                });

                labels = ['Alquilados', 'Disponibles'];
                data = [alquilados, disponibles];
                backgroundColors = ['#17a2b8', '#28a745']; // Azul Info (Alquilados), Verde (Disponibles)
                
                dibujarChartEspacios(labels, data, backgroundColors);
            }
        }
    });
}

function dibujarChartEspacios(labels, data, colors) {
    var ctx = document.getElementById('graficoEspacios').getContext('2d');
    
    if (chartEspacios) { chartEspacios.destroy(); }

    chartEspacios = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}