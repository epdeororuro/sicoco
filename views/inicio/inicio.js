var chartIngresos = null;
var chartEspacios = null;
var chartEficiencia = null;
var chartDeudores = null;

$(document).ready(function() {
    cargarKPIs();
    cargarAnios();
    cargarGraficoEspacios();
    cargarGraficosCxC();

    // Cerrar el modal automáticamente al pedir el reporte
    $('#formCierreCaja').on('submit', function() {
        $('#ModalCierreCaja').modal('hide');
    });

    $('#filtroAnio').on('change', function() {
        cargarGrafico($(this).val());
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

function cargarAnios() {
    $.ajax({
        url: base_url + 'inicio/cargar_anios',
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            if(resp.status === 'success' && resp.data.length > 0) {
                var $select = $('#filtroAnio');
                $select.empty();
                $(resp.data).each(function(i, v) {
                    $select.append('<option value="' + v.anio + '">' + v.anio + '</option>');
                });
                var currentYear = (new Date()).getFullYear();
                if ($select.find('option[value="' + currentYear + '"]').length > 0) {
                    $select.val(currentYear);
                } else {
                    $select.val(resp.data[0].anio);
                }
                cargarGrafico($select.val());
            } else {
                cargarGrafico((new Date()).getFullYear());
            }
        }
    });
}

function cargarGrafico(anio) {
    if (!anio) anio = (new Date()).getFullYear();
    $.ajax({
        url: base_url + 'inicio/cargar_grafico?anio=' + anio,
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            if(resp.status === 'success') {
                var nombresMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                var totales = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

                $(resp.data).each(function(i, v) {
                    var partes = v.mes.split('-');
                    var mesIndex = parseInt(partes[1]) - 1;
                    totales[mesIndex] = parseFloat(v.total);
                });
                
                var mesesEtiquetas = [];
                for(var i = 0; i < 12; i++) {
                    mesesEtiquetas.push(nombresMeses[i] + ' ' + anio);
                }

                dibujarChart(mesesEtiquetas, totales);
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

function cargarGraficosCxC() {
    $.ajax({
        url: base_url + 'inicio/cargar_graficos_cxc',
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            if(resp.status === 'success') {
                // 1. Dibujar gráfico de Eficiencia de Cobranza (Doughnut)
                var cobrado = parseFloat(resp.data.relacion.COBRADO);
                var pendiente = parseFloat(resp.data.relacion.PENDIENTE);
                
                dibujarChartEficiencia(cobrado, pendiente);

                // 2. Dibujar gráfico de Top 5 Deudores (Barras Horizontales)
                var nombresDeudores = [];
                var deudasDeudores = [];
                
                $(resp.data.top_deudores).each(function(i, v) {
                    var nombre = v.CLIENTE.length > 22 ? v.CLIENTE.substring(0, 20) + '...' : v.CLIENTE;
                    nombresDeudores.push(nombre);
                    deudasDeudores.push(parseFloat(v.DEUDA_TOTAL));
                });

                dibujarChartDeudores(nombresDeudores, deudasDeudores);
            }
        }
    });
}

function dibujarChartEficiencia(cobrado, pendiente) {
    var ctx = document.getElementById('graficoEficiencia').getContext('2d');
    
    if (chartEficiencia) { chartEficiencia.destroy(); }

    var total = cobrado + pendiente;
    var porcCobrado = total > 0 ? ((cobrado / total) * 100).toFixed(1) : 0;
    var porcPendiente = total > 0 ? ((pendiente / total) * 100).toFixed(1) : 0;

    chartEficiencia = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Cobrado (' + porcCobrado + '%)', 'Pendiente (' + porcPendiente + '%)'],
            datasets: [{
                data: [cobrado, pendiente],
                backgroundColor: ['#28a745', '#dc3545'],
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            if (label) {
                                label = label.split(' (')[0];
                            }
                            return label + ': Bs. ' + context.raw.toFixed(2);
                        }
                    }
                }
            }
        }
    });
}

function dibujarChartDeudores(labels, data) {
    var ctx = document.getElementById('graficoDeudores').getContext('2d');
    
    if (chartDeudores) { chartDeudores.destroy(); }

    chartDeudores = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Deuda Pendiente (Bs.)',
                data: data,
                backgroundColor: 'rgba(220, 53, 69, 0.85)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1,
                borderRadius: 4,
                barThickness: 20
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Deuda: Bs. ' + context.parsed.x.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return 'Bs. ' + value; }
                    }
                }
            }
        }
    });
}