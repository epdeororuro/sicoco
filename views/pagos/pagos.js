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
       dom: 'Bfrtip',
       buttons: ['excel', 'pdf', 'print']       
    }); 
}

// Evento para imprimir el Cierre de Caja Diario
$(document).ready(function() {
    $('#btn_imprimir_cierre').on('click', function(e) {
        e.preventDefault();
        var fecha = $('#fecha_cierre').val();
        if(fecha) {
            window.open(base_url + 'pagos/imprimir_cierre/' + fecha + '/' + fecha, '_blank');
        } else {
            Swal.fire('Atención', 'Seleccione una fecha válida para el cierre.', 'warning');
        }
    });
});