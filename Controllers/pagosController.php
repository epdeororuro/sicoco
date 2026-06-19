<?php
use Models\Pagos as Pagos;
use Config\sessionController as SessionController;
class pagosController
{
	private $pagos;
	private $usuario_session;
	public function __construct()
	{
		
		$this->usuario_session=new SessionController();
		if ($this->usuario_session->verifica()) {
             $this->pagos=new Pagos();
		
		   }
	else
		{
			header('Location:'. URL . "login");
			exit();
		}
		
	}
	
	public function index()
	{
		//$datos=$this->pagos->lst();
		//return $datos;
	}

	public function listar()
	{
	  $datos=$this->pagos->lst();
	  echo json_encode($datos);
	  exit();
	}

	public function listar_pagos($argumento)
	{
	  $this->pagos->set("idarriendo", $argumento);	
	  $datos=$this->pagos->lst_pagos();
	  echo json_encode($datos);
	  exit();
	}

public function listar_detalle($argumento)
	{
	  $this->pagos->set("idarriendo", $argumento);	
	  $datos=$this->pagos->lst_detalle();
	  echo json_encode($datos);
	  exit();
	}

	public function plan_pagos($argumento)
	{
	  try {
	      $this->pagos->set("idarriendo", $argumento);	
	      $datos = $this->pagos->plan_pagos();
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'success', 'data' => $datos]);
	  } catch (\Exception $e) {
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
	  }
	  exit();
	}

	public function imprimir_recibo($idpago)
	{
	    $this->pagos->set("idpago", $idpago);
	    $datos = $this->pagos->obtener_datos_recibo();

	    if (!$datos) {
	        die("Error: Recibo no encontrado.");
	    }

	    // Asegúrate de tener el archivo FPDF en esta ruta
	    require_once ROOT . "libs/fpdf/fpdf.php";

	    $pdf = new \FPDF('P', 'mm', 'Letter');
	    $pdf->AddPage();
	    $pdf->SetAutoPageBreak(false);

	    // --- DIBUJAR 2 RECIBOS EN LA MISMA HOJA ---
	    $this->dibujar_recibo($pdf, $datos, 10, 'ORIGINAL - CLIENTE');
	    
	    // Línea de corte (Guiones)
	    $pdf->SetDrawColor(150, 150, 150);
	    for($i = 10; $i < 200; $i += 5) {
	        $pdf->Line($i, 135, $i+2, 135);
	    }

	    $this->dibujar_recibo($pdf, $datos, 145, 'COPIA - CAJA EPDEOR');

	    if (ob_get_length()) ob_clean();
	    
	    $pdf_b64 = base64_encode($pdf->Output('S'));
	    $url = URL;
	    echo "<!DOCTYPE html>
			<html lang='es'>
			<head>
				<meta charset='UTF-8'>
				<title>SICOCO</title>
				<link rel='icon' href='{$url}img/logos/favicon.ico' type='image/x-icon'>
				<style>body,html{margin:0;padding:0;height:100%;overflow:hidden;background-color:#525659;} iframe{width:100%;height:100%;border:none;}</style>
			</head>
			<body>
				<iframe src='data:application/pdf;base64,{$pdf_b64}'></iframe>
			</body>
			</html>";
	    exit();
	}

	private function dibujar_recibo($pdf, $datos, $y, $tipo)
	{
	    $meses = ['01'=>'Enero', '02'=>'Febrero', '03'=>'Marzo', '04'=>'Abril', '05'=>'Mayo', '06'=>'Junio', '07'=>'Julio', '08'=>'Agosto', '09'=>'Septiembre', '10'=>'Octubre', '11'=>'Noviembre', '12'=>'Diciembre'];
	    
	    $partes = explode('-', $datos['PERIODO']);
	    $mes_texto = $meses[$partes[1]] . ' de ' . $partes[0];

	    // RUTAS A TUS IMÁGENES
	    $img_izq = ROOT . 'img/logos/logo_1.jpg'; 
	    $img_cen = ROOT . 'img/logos/logo_2.jpg'; 
	    $img_der = ROOT . 'img/logos/logo_3.jpg'; 

	    if (file_exists($img_izq)) $pdf->Image($img_izq, 15, $y, 45);
	    if (file_exists($img_cen)) $pdf->Image($img_cen, 75, $y, 60);
	    if (file_exists($img_der)) $pdf->Image($img_der, 175, $y, 25);

	    // --- MARCA DE AGUA DE SEGURIDAD PARA ANULACIONES ---
	    if (isset($datos['ESTADO_RECIBO']) && $datos['ESTADO_RECIBO'] === 'ANULADO') {
	        $pdf->SetFont('Arial', 'B', 45);
	        $pdf->SetTextColor(255, 200, 200);
	        $pdf->SetXY(20, $y + 35);
	        $pdf->Cell(170, 20, utf8_decode('*** ANULADO ***'), 0, 1, 'C');
	        $pdf->SetTextColor(0, 0, 0); // Restaurar a negro
	    }

	    $pdf->SetFont('Arial', 'B', 14);
	    $pdf->SetXY(45, $y + 5);
	    $pdf->Cell(120, 6, utf8_decode(''), 0, 1, 'C');
	    $pdf->SetFont('Arial', 'B', 10);
	    $pdf->SetXY(45, $y + 11);
	    $pdf->Cell(120, 5, utf8_decode('COMPROBANTE DE PAGO DE ARRENDAMIENTO - DEUDA'), 0, 1, 'C');

	    $pdf->SetFont('Arial', 'B', 8);
	    $pdf->SetXY(160, $y + 25);
	    $pdf->Cell(40, 5, utf8_decode($tipo), 0, 1, 'R');

	    $pdf->SetFont('Arial', 'B', 10);
	    $pdf->SetXY(15, $y + 30);
	    $nro = !empty($datos['NRO_RECIBO']) ? $datos['NRO_RECIBO'] : str_pad($datos['IDPAGO'], 6, '0', STR_PAD_LEFT);
	    $pdf->Cell(90, 6, utf8_decode('RECIBO NRO: ' . $nro), 0, 0, 'L');
	    $pdf->SetFont('Arial', '', 10);
	    $pdf->Cell(90, 6, utf8_decode('Fecha y Hora: ' . date('d/m/Y H:i', strtotime($datos['FECHA_PAGO']))), 0, 1, 'R');
	    $pdf->Ln(2);

	    $pdf->SetFillColor(240, 240, 240);
	    $pdf->SetDrawColor(200, 200, 200);
	    $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Arrendatario:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(95, 7, utf8_decode(' ' . $datos['CLIENTE']), 1, 0, 'L');
	    $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 7, utf8_decode(' CI:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(35, 7, utf8_decode(' ' . $datos['CEDULA']), 1, 1, 'L');

	    $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Nro. Contrato:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(95, 7, utf8_decode(' ' . $datos['CONTRATO']), 1, 0, 'L');
	    $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 7, utf8_decode(' Actividad:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 7); $pdf->Cell(35, 7, utf8_decode(' ' . substr($datos['ACTIVIDAD'], 0, 20)), 1, 1, 'L');

	    $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Corresponde a:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(150, 7, utf8_decode(' MES DE ' . strtoupper($mes_texto)), 1, 1, 'L');
	    
		// --- NUEVA SECCIÓN: DETALLES DE PAGO Y FACTURACIÓN ---
		$pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Método de Pago:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(60, 7, utf8_decode(' ' . $datos['METODO_PAGO']), 1, 0, 'L');
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Nro. Factura SIAT:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(55, 7, utf8_decode(' ' . $datos['NRO_FACTURA_SIAT']), 1, 1, 'L');
		if($datos['METODO_PAGO'] !== 'EFECTIVO' && !empty($datos['NRO_COMPROBANTE'])) {
			$pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Nro. Transacción:'), 1, 0, 'L', true);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(150, 7, utf8_decode(' ' . $datos['NRO_COMPROBANTE']), 1, 1, 'L');
		}
	    $pdf->Ln(5);

	    $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 11); $pdf->Cell(140, 10, utf8_decode(' TOTAL PAGADO:'), 1, 0, 'R', true);
	    $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(45, 10, utf8_decode('Bs. ' . number_format($datos['MONTO'], 2)), 1, 1, 'C');
	    $pdf->Ln(15);

	    $pdf->SetFont('Arial', '', 9);
	    $pdf->SetX(30); $pdf->Cell(60, 4, '_____________________________', 0, 0, 'C');
	    $pdf->SetX(120); $pdf->Cell(60, 4, '_____________________________', 0, 1, 'C');
	    $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Firma del Cajero'), 0, 0, 'C');
	    $pdf->SetX(120); $pdf->Cell(60, 4, utf8_decode('Firma del Arrendatario'), 0, 1, 'C');
	    $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Cajero: ' . $datos['USR']), 0, 0, 'C');
	}

	public function realizar_pago($idpago)
	{
	    try {
			// La data de pago viene por POST desde el nuevo Swal
	        $this->pagos->set("idpago", $idpago);
            $cadena = $this->usuario_session->getCurrentUser();
	        $this->pagos->set("usr", $cadena['nombre']);
            
            // Obtenemos el nuevo número de recibo usando la tabla correlativos
            $this->pagos->set("nro_recibo", $this->pagos->obtener_siguiente_recibo());

			// Nuevos campos FASE 2
			$this->pagos->set("metodo_pago", isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : 'EFECTIVO');
			$this->pagos->set("nro_comprobante", isset($_POST['nro_comprobante']) ? $_POST['nro_comprobante'] : null);
			$this->pagos->set("nro_factura_siat", isset($_POST['nro_factura_siat']) ? $_POST['nro_factura_siat'] : null);
            
	        $this->pagos->registrar_pago();
            
            // Disparador de Liberación Automática
            $this->pagos->verificar_y_liberar_contrato_por_pago($idpago);

            // Registrar en auditoría
            $recibo = $this->pagos->get("nro_recibo");
            $this->usuario_session->registrarActividad('PAGO_REGISTRADO', 'Registró el cobro individual del pago ID: ' . $idpago . ' (Recibo Nro: ' . $recibo . ')');

	        if (ob_get_length()) ob_clean();
	        echo json_encode(['status' => 'success', 'message' => 'Pago realizado correctamente']);
	    } catch (\Exception $e) {
	        if (ob_get_length()) ob_clean();
	        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
	    }
	    exit();
	}

	public function imprimir_cierre($fecha_inicio = null)
	{
	    if(isset($_POST['inicio']) && $_POST['inicio'] != '') {
	        $fecha_inicio = $_POST['inicio'];
	    } elseif(isset($_GET['inicio']) && $_GET['inicio'] != '') {
	        $fecha_inicio = $_GET['inicio'];
	    }
	    
	    $fecha_fin = isset($_POST['fin']) ? $_POST['fin'] : (isset($_GET['fin']) ? $_GET['fin'] : null);

	    if(!$fecha_inicio) {
	        $fecha_inicio = date('Y-m-d');
	    }
	    if(!$fecha_fin) {
	        $fecha_fin = $fecha_inicio; // Si solo envían una fecha, asume que es reporte de un día
	    }

	    $datos = $this->pagos->cierre_caja_diario($fecha_inicio, $fecha_fin);

	    // Registrar en el log de cierres para auditoría contable
	    $cadena = $this->usuario_session->getCurrentUser();
	    $this->pagos->registrar_log_cierre($fecha_inicio, $fecha_fin, $cadena['nombre']);

	    require_once ROOT . "libs/fpdf/fpdf.php";
	    $pdf = new \FPDF('P', 'mm', 'Letter');
	    $pdf->AddPage();
	    $pdf->SetAutoPageBreak(true, 15);

	    // Logos Institucionales
	    $img_izq = ROOT . 'img/logos/logo_1.jpg'; 
	    $img_cen = ROOT . 'img/logos/logo_2.jpg'; 
	    $img_der = ROOT . 'img/logos/logo_3.jpg'; 

	    if (file_exists($img_izq)) $pdf->Image($img_izq, 15, 10, 35);
	    if (file_exists($img_cen)) $pdf->Image($img_cen, 75, 10, 60);
	    if (file_exists($img_der)) $pdf->Image($img_der, 175, 10, 20);

	    $pdf->Ln(20);
	    $pdf->SetFont('Arial', 'B', 14);
	    $pdf->Cell(0, 6, utf8_decode('REPORTE FINANCIERO DE INGRESOS'), 0, 1, 'C');
	    $pdf->SetFont('Arial', '', 11);
	    
	    if ($fecha_inicio == $fecha_fin) {
	        $pdf->Cell(0, 6, utf8_decode('Fecha de Transacciones: ' . date('d/m/Y', strtotime($fecha_inicio))), 0, 1, 'C');
	    } else {
	        $pdf->Cell(0, 6, utf8_decode('Periodo del: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($fecha_fin))), 0, 1, 'C');
	    }
	    $pdf->Ln(5);

	    if (!$datos || count($datos) == 0) {
	        $pdf->SetFont('Arial', 'B', 12);
	        $pdf->Cell(0, 20, utf8_decode('No se registraron cobros en el periodo seleccionado.'), 0, 1, 'C');
	        if (ob_get_length()) ob_clean();
	        
	        $pdf_b64 = base64_encode($pdf->Output('S'));
	        $url = URL;
	        echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>SICOCO</title>
    <link rel='icon' href='{$url}img/logos/favicon.ico' type='image/x-icon'>
    <style>body,html{margin:0;padding:0;height:100%;overflow:hidden;background-color:#525659;} iframe{width:100%;height:100%;border:none;}</style>
</head>
<body>
    <iframe src='data:application/pdf;base64,{$pdf_b64}'></iframe>
</body>
</html>";
	        exit();
	    }

	    $gran_total = 0;
	    $cajero_actual = "";
	    $subtotal_cajero = 0;

	    foreach($datos as $d) {
	        // Detección de cambio de cajero para subtotales y cabeceras
	        if($cajero_actual != $d['CAJERO']) {
	            if($cajero_actual != "") {
	                $pdf->SetFillColor(230, 230, 230);
	                $pdf->SetFont('Arial', 'B', 9);
	                $pdf->Cell(165, 6, utf8_decode('SUBTOTAL RECAUDADO POR ' . $cajero_actual . ': '), 1, 0, 'R', true);
	                $pdf->Cell(30, 6, number_format($subtotal_cajero, 2), 1, 1, 'R', true);
	                $pdf->Ln(3);
	            }
	            $cajero_actual = $d['CAJERO'];
	            $subtotal_cajero = 0;
	            $pdf->SetFillColor(210, 230, 255);
	            $pdf->SetFont('Arial', 'B', 10);
	            $pdf->Cell(0, 7, utf8_decode(' CAJERO / USUARIO: ' . strtoupper($cajero_actual)), 1, 1, 'L', true);
	            $pdf->SetFillColor(230, 230, 230);
	            $pdf->SetFont('Arial', 'B', 8);
	            $pdf->Cell(15, 6, 'RECIBO', 1, 0, 'C', true);
	            $pdf->Cell(10, 6, 'HORA', 1, 0, 'C', true);
	            $pdf->Cell(60, 6, 'CLIENTE', 1, 0, 'C', true);
	            $pdf->Cell(35, 6, 'CONTRATO', 1, 0, 'C', true);
	            $pdf->Cell(45, 6, 'PERIODOS', 1, 0, 'C', true);
	            $pdf->Cell(30, 6, 'TOTAL (Bs)', 1, 1, 'C', true);
	        }

	        // Fila de datos del recibo
	        $pdf->SetFont('Arial', '', 8);
	        $pdf->Cell(15, 6, $d['NRO_RECIBO'], 1, 0, 'C');
	        $pdf->Cell(10, 6, date('H:i', strtotime($d['HORA'])), 1, 0, 'C');
	        if ($d['ESTADO_RECIBO'] == 'ANULADO') {
	            $pdf->SetTextColor(255, 0, 0);
	            $pdf->Cell(60, 6, utf8_decode('*** ANULADO *** ' . substr($d['CLIENTE'], 0, 15)), 1, 0, 'L');
	            $pdf->SetTextColor(0, 0, 0);
	        } else {
	            $pdf->Cell(60, 6, utf8_decode(substr($d['CLIENTE'], 0, 30)), 1, 0, 'L');
	        }
	        $pdf->Cell(35, 6, utf8_decode(substr($d['CONTRATO'], 0, 25)), 1, 0, 'C');
	        $pdf->Cell(45, 6, utf8_decode(substr($d['PERIODOS'], 0, 25)), 1, 0, 'C');
	        $pdf->Cell(30, 6, number_format($d['TOTAL'], 2), 1, 1, 'R');

	        $subtotal_cajero += $d['TOTAL'];
	        $gran_total += $d['TOTAL'];
	    }

	    // Imprimir subtotal del último cajero del bucle
	    $pdf->SetFillColor(230, 230, 230);
	    $pdf->SetFont('Arial', 'B', 9);
	    $pdf->Cell(165, 6, utf8_decode('SUBTOTAL RECAUDADO POR ' . $cajero_actual . ': '), 1, 0, 'R', true);
	    $pdf->Cell(30, 6, number_format($subtotal_cajero, 2), 1, 1, 'R', true);
	    $pdf->Ln(5);

	    // TOTAL DE INGRESOS POR ALQUILERES DE LA TABLA
	    $pdf->SetFillColor(200, 255, 200);
	    $pdf->SetFont('Arial', 'B', 12);
	    $pdf->Cell(165, 8, utf8_decode('TOTAL INGRESOS POR ALQUILERES: '), 1, 0, 'R', true);
	    $pdf->Cell(30, 8, 'Bs. ' . number_format($gran_total, 2), 1, 1, 'R', true);

	    // -------------------------------------------------------------
	    // ARQUEO CONSOLIDADO (Alquileres + Garantías - Devoluciones)
	    // -------------------------------------------------------------
	    $resumen = $this->pagos->resumen_caja_consolidado($fecha_inicio, $fecha_fin);

	    $pdf->Ln(10);
	    $pdf->SetFont('Arial', 'B', 12);
	    $pdf->Cell(0, 8, utf8_decode('ARQUEO FÍSICO CONSOLIDADO DE CAJA'), 0, 1, 'C');
	    $pdf->SetFont('Arial', 'B', 10);

	    $pdf->SetX(45);
	    $pdf->SetFillColor(240, 240, 240);
	    $pdf->Cell(90, 6, utf8_decode('(+) Ingresos por Alquileres:'), 1, 0, 'L', true);
	    $pdf->Cell(40, 6, number_format($resumen['ingreso_alquileres'], 2), 1, 1, 'R');

	    $pdf->SetX(45);
	    $pdf->Cell(90, 6, utf8_decode('(+) Ingresos por Garantías (Nuevas):'), 1, 0, 'L', true);
	    $pdf->Cell(40, 6, number_format($resumen['ingreso_garantias'], 2), 1, 1, 'R');

	    $pdf->SetX(45);
	    $pdf->Cell(90, 6, utf8_decode('(-) Egresos Varios (Devolución Garantías):'), 1, 0, 'L', true);
	    $pdf->Cell(40, 6, number_format($resumen['egresos_garantias'], 2), 1, 1, 'R');

	    $pdf->SetX(45);
	    $pdf->SetFillColor(150, 200, 150);
	    $pdf->SetFont('Arial', 'B', 11);
	    $pdf->Cell(90, 8, utf8_decode('(=) TOTAL EFECTIVO FÍSICO EN CAJA:'), 1, 0, 'L', true);
	    $pdf->Cell(40, 8, 'Bs. ' . number_format($resumen['total_efectivo'], 2), 1, 1, 'R', true);

	    // Firmas de Conformidad
	    $pdf->Ln(20);
	    $pdf->SetFont('Arial', '', 9);
	    $pdf->Cell(95, 4, '_____________________________', 0, 0, 'C');
	    $pdf->Cell(95, 4, '_____________________________', 0, 1, 'C');
	    $pdf->Cell(95, 4, utf8_decode('Revisado por (Contabilidad)'), 0, 0, 'C');
	    $pdf->Cell(95, 4, utf8_decode('Aprobado por (Gerencia)'), 0, 1, 'C');

	    if (ob_get_length()) ob_clean();
	    
	    $pdf_b64 = base64_encode($pdf->Output('S'));
	    $url = URL;
	    echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>SICOCO</title>
    <link rel='icon' href='{$url}img/logos/favicon.ico' type='image/x-icon'>
    <style>body,html{margin:0;padding:0;height:100%;overflow:hidden;background-color:#525659;} iframe{width:100%;height:100%;border:none;}</style>
</head>
<body>
    <iframe src='data:application/pdf;base64,{$pdf_b64}'></iframe>
</body>
</html>";
	    exit();
	}

	public function historial() {
	    try {
	        $datos = $this->pagos->historial_caja();
	        if (ob_get_length()) ob_clean();
	        $json = json_encode(['status' => 'success', 'data' => $datos]);
	        if ($json === false) {
	            echo json_encode(['status' => 'error', 'data' => [], 'message' => 'Error JSON: ' . json_last_error_msg()]);
	        } else {
	            echo $json;
	        }
	    } catch (\Exception $e) {
	        if (ob_get_length()) ob_clean();
	        echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
	    }
	    exit();
	}

	public function reimprimir($nro_recibo) {
	    if(!$nro_recibo) {
	        die("Error: Número de recibo no especificado.");
	    }

	    // 1. Verificar si el recibo está ANULADO (Lo buscamos en la bóveda)
	    $db = new \Models\Conexion();
	    $stmt = $db->conexion->prepare("SELECT * FROM log_estornos WHERE NRO_RECIBO = ?");
	    $stmt->execute([$nro_recibo]);
	    $log = $stmt->fetch(\PDO::FETCH_ASSOC);
	    
	    if ($log) {
	        $datos_base = [
	            'RECIBO_NRO' => $log['NRO_RECIBO'],
	            'FECHA_PAGO' => $log['FECHA_COBRO'],
	            'CLIENTE' => $log['CLIENTE'],
	            'CEDULA' => $log['CEDULA'],
	            'CONTRATO' => $log['CONTRATO'],
	            'ACTIVIDAD' => $log['ACTIVIDAD'],
	            'MONTO' => $log['MONTO_TOTAL'],
	            'USR' => $log['CAJERO_ORIGINAL'],
	            'ESTADO_RECIBO' => 'ANULADO',
	            'METODO_PAGO' => $log['METODO_PAGO'],
	            'NRO_COMPROBANTE' => $log['NRO_COMPROBANTE'],
	            'NRO_FACTURA_SIAT' => $log['NRO_FACTURA_SIAT'],
	            'TEXTO_PERIODOS_MANUAL' => $log['PERIODOS_COBRADOS']
	        ];
	        require_once ROOT . "libs/fpdf/fpdf.php";
	        $pdf = new \FPDF('P', 'mm', 'Letter');
	        $pdf->AddPage();
	        $pdf->SetAutoPageBreak(false);
	        $this->dibujar_recibo_multiple($pdf, $datos_base, 10, 'ORIGINAL - CLIENTE');
	        $pdf->SetDrawColor(150, 150, 150);
	        for($i = 10; $i < 200; $i += 5) $pdf->Line($i, 135, $i+2, 135);
	        $this->dibujar_recibo_multiple($pdf, $datos_base, 145, 'COPIA - CAJA EPDEOR');
	        if (ob_get_length()) ob_clean();
	        $pdf_b64 = base64_encode($pdf->Output('S'));
	        echo "<!DOCTYPE html><html lang='es'><head><style>body,html{margin:0;padding:0;height:100%;overflow:hidden;background-color:#525659;} iframe{width:100%;height:100%;border:none;}</style></head><body><iframe src='data:application/pdf;base64,{$pdf_b64}'></iframe></body></html>";
	        exit();
	    }
	    
	    // 2. Si no está anulado, fluye normalmente
	    $this->pagos->set("nro_recibo", $nro_recibo);
	    $pagos = $this->pagos->obtener_ids_por_recibo();
	    
	    if (!$pagos || count($pagos) == 0) {
	        die("Error: El recibo solicitado no existe o no tiene pagos asociados.");
	    }
	    
	    $ids = [];
	    foreach($pagos as $p) {
	        $ids[] = $p['IDPAGO'];
	    }
	    
	    // Simulamos la llamada múltiple pasándole los IDs encontrados
	    $_GET['ids'] = implode(',', $ids);
	    $this->imprimir_recibo_multiple();
	}

	public function realizar_pago_multiple()
	{
	    if(isset($_POST['idpagos'])) {
	        try {
	            $ids = explode(',', $_POST['idpagos']);
				if(empty($ids) || $ids[0] == '') {
					throw new \Exception("No se seleccionaron pagos válidos.");
				}

	            $cadena = $this->usuario_session->getCurrentUser();
	            $usr = $cadena['nombre'];
	            
	            // Obtenemos un único número correlativo transaccional para todo el bloque
	            $nro_recibo = $this->pagos->obtener_siguiente_recibo();

				// Nuevos campos FASE 2
				$metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : 'EFECTIVO';
				$nro_comprobante = isset($_POST['nro_comprobante']) ? $_POST['nro_comprobante'] : null;
				$nro_factura_siat = isset($_POST['nro_factura_siat']) ? $_POST['nro_factura_siat'] : null;

	            foreach($ids as $idpago) {
	                $this->pagos->set("idpago", $idpago);
	                $this->pagos->set("usr", $usr);
	                $this->pagos->set("nro_recibo", $nro_recibo);
					$this->pagos->set("metodo_pago", $metodo_pago);
					$this->pagos->set("nro_comprobante", $nro_comprobante);
					$this->pagos->set("nro_factura_siat", $nro_factura_siat);
	                $this->pagos->registrar_pago();
	            }

	            // Disparador de Liberación Automática (Usamos el último ID del bloque para verificar)
	            $this->pagos->verificar_y_liberar_contrato_por_pago(end($ids));

                // Registrar en auditoría
                $this->usuario_session->registrarActividad('PAGO_MULTIPLE_REGISTRADO', 'Registró cobros acumulados para los pagos IDs: ' . $_POST['idpagos'] . ' (Recibo Nro: ' . $nro_recibo . ')');

	            if (ob_get_length()) ob_clean();
	            echo json_encode(['status' => 'success', 'message' => 'Pagos realizados correctamente']);
	        } catch (\Exception $e) {
	            if (ob_get_length()) ob_clean();
	            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
	        }
	    }
	    exit();
	}

	public function imprimir_recibo_multiple()
	{
	    if(!isset($_GET['ids'])) {
	        die("Error: No se especificaron los pagos.");
	    }
	    
	    $ids = explode(',', $_GET['ids']);
	    if(count($ids) == 0) die("Error: Lista de pagos vacía.");

	    // Optimización: 1 sola consulta a la base de datos en lugar de un bucle (N+1)
	    $lista_datos = $this->pagos->obtener_datos_recibos_multiples($ids);

	    if (!$lista_datos || count($lista_datos) == 0) {
	        die("Error: Recibo no encontrado.");
	    }

	    $datos_base = $lista_datos[0];
	    $total_monto = 0;
	    $periodos = [];
	    
	    foreach($lista_datos as $d) {
	        $total_monto += $d['MONTO'];
	        $periodos[] = $d['PERIODO'];
	    }

	    $datos_base['MONTO'] = $total_monto;
	    $datos_base['PERIODOS_ARRAY'] = $periodos;
        
        $datos_base['RECIBO_NRO'] = !empty($datos_base['NRO_RECIBO']) ? $datos_base['NRO_RECIBO'] : str_pad($datos_base['IDPAGO'], 6, '0', STR_PAD_LEFT);

	    require_once ROOT . "libs/fpdf/fpdf.php";

	    $pdf = new \FPDF('P', 'mm', 'Letter');
	    $pdf->AddPage();
	    $pdf->SetAutoPageBreak(false);

	    $this->dibujar_recibo_multiple($pdf, $datos_base, 10, 'ORIGINAL - CLIENTE');
	    
	    $pdf->SetDrawColor(150, 150, 150);
	    for($i = 10; $i < 200; $i += 5) {
	        $pdf->Line($i, 135, $i+2, 135);
	    }

	    $this->dibujar_recibo_multiple($pdf, $datos_base, 145, 'COPIA - CAJA EPDEOR');

	    if (ob_get_length()) ob_clean();
	    
	    $pdf_b64 = base64_encode($pdf->Output('S'));
	    $url = URL;
	    echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>SICOCO</title>
    <link rel='icon' href='{$url}img/logos/favicon.ico' type='image/x-icon'>
    <style>body,html{margin:0;padding:0;height:100%;overflow:hidden;background-color:#525659;} iframe{width:100%;height:100%;border:none;}</style>
</head>
<body>
    <iframe src='data:application/pdf;base64,{$pdf_b64}'></iframe>
</body>
</html>";
	    exit();
	}

	private function dibujar_recibo_multiple($pdf, $datos, $y, $tipo)
	{
	    $meses = ['01'=>'Enero', '02'=>'Febrero', '03'=>'Marzo', '04'=>'Abril', '05'=>'Mayo', '06'=>'Junio', '07'=>'Julio', '08'=>'Agosto', '09'=>'Septiembre', '10'=>'Octubre', '11'=>'Noviembre', '12'=>'Diciembre'];
	    
        $text_periodos = "";
        if (isset($datos['TEXTO_PERIODOS_MANUAL'])) {
            $text_periodos = $datos['TEXTO_PERIODOS_MANUAL'];
        } elseif (isset($datos['PERIODOS_ARRAY'])) {
            $arr = [];
            foreach($datos['PERIODOS_ARRAY'] as $p) {
                $partes = explode('-', $p);
                $arr[] = $meses[$partes[1]] . '/' . $partes[0];
            }
            if(count($arr) > 3) {
                $text_periodos = $arr[0] . ' al ' . end($arr);
            } else {
                $text_periodos = implode(', ', $arr);
            }
        } else {
            $partes = explode('-', $datos['PERIODO']);
            $text_periodos = $meses[$partes[1]] . ' de ' . $partes[0];
        }

        // Reutilizamos toda la estructura gráfica de tu recibo (Solo cambiamos el contenido textual)
	    $img_izq = ROOT . 'img/logos/logo_1.jpg'; 
	    $img_cen = ROOT . 'img/logos/logo_2.jpg'; 
	    $img_der = ROOT . 'img/logos/logo_3.jpg'; 

	    if (file_exists($img_izq)) $pdf->Image($img_izq, 15, $y, 45);
	    if (file_exists($img_cen)) $pdf->Image($img_cen, 75, $y, 60);
	    if (file_exists($img_der)) $pdf->Image($img_der, 175, $y, 25);

	    // --- MARCA DE AGUA DE SEGURIDAD PARA ANULACIONES ---
	    if (isset($datos['ESTADO_RECIBO']) && $datos['ESTADO_RECIBO'] === 'ANULADO') {
	        $pdf->SetFont('Arial', 'B', 45);
	        $pdf->SetTextColor(255, 200, 200);
	        $pdf->SetXY(20, $y + 35);
	        $pdf->Cell(170, 20, utf8_decode('*** ANULADO ***'), 0, 1, 'C');
	        $pdf->SetTextColor(0, 0, 0); // Restaurar a negro
	    }

	    $pdf->SetFont('Arial', 'B', 14);
	    $pdf->SetXY(45, $y + 5);
	    $pdf->Cell(120, 6, utf8_decode(''), 0, 1, 'C');
	    $pdf->SetFont('Arial', 'B', 10);
	    $pdf->SetXY(45, $y + 11);
	    $pdf->Cell(120, 5, utf8_decode('COMPROBANTE DE PAGO DE ARRENDAMIENTO - DEUDA'), 0, 1, 'C');

	    $pdf->SetFont('Arial', 'B', 8);
	    $pdf->SetXY(160, $y + 25);
	    $pdf->Cell(40, 5, utf8_decode($tipo), 0, 1, 'R');

	    $pdf->SetFont('Arial', 'B', 10);
	    $pdf->SetXY(15, $y + 30);
        $nro = isset($datos['RECIBO_NRO']) ? $datos['RECIBO_NRO'] : str_pad($datos['IDPAGO'], 6, '0', STR_PAD_LEFT);
	    $pdf->Cell(90, 6, utf8_decode('RECIBO NRO: ' . $nro), 0, 0, 'L');
	    $pdf->SetFont('Arial', '', 10);
	    $pdf->Cell(90, 6, utf8_decode('Fecha y Hora: ' . date('d/m/Y H:i', strtotime($datos['FECHA_PAGO']))), 0, 1, 'R');
	    $pdf->Ln(2);

	    $pdf->SetFillColor(240, 240, 240);
	    $pdf->SetDrawColor(200, 200, 200);
	    $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Arrendatario:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(95, 7, utf8_decode(' ' . $datos['CLIENTE']), 1, 0, 'L');
	    $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 7, utf8_decode(' CI:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(35, 7, utf8_decode(' ' . $datos['CEDULA']), 1, 1, 'L');

	    $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Nro. Contrato:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(95, 7, utf8_decode(' ' . $datos['CONTRATO']), 1, 0, 'L');
	    $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 7, utf8_decode(' Actividad:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 7); $pdf->Cell(35, 7, utf8_decode(' ' . substr($datos['ACTIVIDAD'], 0, 20)), 1, 1, 'L');

	    $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Corresponde a:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(150, 7, utf8_decode(' MES(ES): ' . strtoupper($text_periodos)), 1, 1, 'L');
	    $pdf->Ln(5);

		// --- NUEVA SECCIÓN: DETALLES DE PAGO Y FACTURACIÓN ---
		$pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Método de Pago:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(60, 7, utf8_decode(' ' . $datos['METODO_PAGO']), 1, 0, 'L');
		$pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Nro. Factura SIAT:'), 1, 0, 'L', true);
	    $pdf->SetFont('Arial', '', 9); $pdf->Cell(55, 7, utf8_decode(' ' . $datos['NRO_FACTURA_SIAT']), 1, 1, 'L');
		if($datos['METODO_PAGO'] !== 'EFECTIVO' && !empty($datos['NRO_COMPROBANTE'])) {
			$pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Nro. Transacción:'), 1, 0, 'L', true);
			$pdf->SetFont('Arial', '', 9); $pdf->Cell(150, 7, utf8_decode(' ' . $datos['NRO_COMPROBANTE']), 1, 1, 'L');
		}

	    $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 11); $pdf->Cell(140, 10, utf8_decode(' TOTAL PAGADO:'), 1, 0, 'R', true);
	    $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(45, 10, utf8_decode('Bs. ' . number_format($datos['MONTO'], 2)), 1, 1, 'C');
	    $pdf->Ln(15);

	    $pdf->SetFont('Arial', '', 9);
	    $pdf->SetX(30); $pdf->Cell(60, 4, '_____________________________', 0, 0, 'C');
	    $pdf->SetX(120); $pdf->Cell(60, 4, '_____________________________', 0, 1, 'C');
	    $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Firma del Cajero'), 0, 0, 'C');
	    $pdf->SetX(120); $pdf->Cell(60, 4, utf8_decode('Firma del Arrendatario'), 0, 1, 'C');
	    $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Cajero: ' . $datos['USR']), 0, 0, 'C');
	}

	
} // fin clase
?>