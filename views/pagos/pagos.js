// =====================================================================
// HISTORIAL Y AUDITORÍA DE CAJA (VISTA PRINCIPAL PAGOS)
// =====================================================================
function ListarHistorialCaja(){
  $("#TablaHistorial").DataTable({     
   "responsive":true,
   "destroy":true, 
   "order": [[1, "desc"]], // Ordenar por fecha de cobro más reciente
   "autoWidth": false,
      "ajax":{
        "url": base_url+'pagos/historial',
        "dataSrc": function(json) { 
            if (json.status === 'error') {
                console.error("Error backend BD:", json.message);
            }
            return json.data ? json.data : []; 
        }
      },
      "columns":[
      {"data": null, "searchable": false, "orderable": false, "render": function (data, type, row, meta) {
          return meta.row + 1; // Contador automático
      }},
      {"data": "NRO_RECIBO", "render": function(data){
          return "<span class='text-danger font-weight-bold'><i class='fas fa-receipt'></i> " + data + "</span>";
      }},
      {"data": "FECHA"},
      {"data": "CLIENTE"},
      {"data": "CONTRATO"},
      {"data": "PERIODOS", "render": function(data) { return "<small>" + data + "</small>"; }},
      {"data": "TOTAL", "render": function(data){
          return "<strong class='text-success'>Bs. " + parseFloat(data).toFixed(2) + "</strong>";
      }},
      {"data": "CAJERO"},
      {"data": null, "render": function(data, type, row) {
          return "<a href='"+base_url+"pagos/reimprimir/"+row.NRO_RECIBO+"' target='_blank' class='btn btn-info btn-sm' title='Reimprimir Copia de Recibo'><i class='fas fa-print'></i> Reimprimir</a>";
      }}
      ],     
    }); 
}

// Evento para imprimir el Cierre de Caja Diario
$(document).ready(function() {
    $('#btn_imprimir_cierre').on('click', function(e) {
        e.preventDefault();
        var fecha = $('#fecha_cierre').val();
        if(fecha) {
            // Abrimos el PDF en una nueva pestaña usando POST
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = base_url + 'pagos/imprimir_cierre';
            form.target = '_blank';

            var tokenVal = (typeof csrf_token !== 'undefined') ? csrf_token : $('meta[name="csrf-token"]').attr('content');
            var inputCsrf = document.createElement('input');
            inputCsrf.type = 'hidden';
            inputCsrf.name = 'csrf_token';
            inputCsrf.value = tokenVal || '';
            form.appendChild(inputCsrf);

            var inputInicio = document.createElement('input');
            inputInicio.type = 'hidden';
            inputInicio.name = 'inicio';
            inputInicio.value = fecha;
            form.appendChild(inputInicio);

            var inputFin = document.createElement('input');
            inputFin.type = 'hidden';
            inputFin.name = 'fin';
            inputFin.value = fecha;
            form.appendChild(inputFin);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        } else {
            Swal.fire('Atención', 'Seleccione una fecha válida para el cierre.', 'warning');
        }
    });
});