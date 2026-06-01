$(document).ready(function() {
    cargarKPIs();
    cargarGrafico();
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
                
                $(resp.data).each(function(i, v) {
                    meses.push(v.mes); // Ej: 2026-05
                    totales.push(parseFloat(v.total));
                });
                
                dibujarChart(meses, totales);
            }
        }
    });
}

function dibujarChart(labels, data) {
    var ctx = document.getElementById('graficoIngresos').getContext('2d');
    new Chart(ctx, {
        type: 'bar', // Gráfico de barras
        data: {
            labels: labels,
            datasets: [{
                label: 'Ingresos Mensuales (Bs.)',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                borderWidth: 1,
                data: data
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}