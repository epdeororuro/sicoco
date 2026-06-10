<?php
use Models\Cumplimiento as Cumplimiento;
use Config\sessionController as SessionController;

class cumplimientoController {
    private $cumplimiento;
    private $usuario_session;

    public function __construct() {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->cumplimiento = new Cumplimiento();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index() {}

    public function listar() {
        $datos = $this->cumplimiento->lst();
        echo json_encode(['data' => $datos]);
        exit();
    }

    public function listar_areas() {
        $datos = $this->cumplimiento->lst_areas();
        echo json_encode(['data' => $datos]);
        exit();
    }

    public function listar_catalogo_por_area($idarea = null) {
        if(!$idarea){ echo json_encode(['data' => []]); exit(); }
        $datos = $this->cumplimiento->lst_catalogo_por_area($idarea);
        echo json_encode(['data' => $datos]);
        exit();
    }

    public function add() {
        if($_POST){
            if (empty($_POST['txt_cite']) || empty($_POST['txt_ci']) || empty($_POST['txt_nombre']) || empty($_POST['SelItemCatalogo']) || $_POST['SelItemCatalogo'] == '0') {
                echo json_encode(['status' => 'error', 'message' => 'Complete todos los campos.']);
                exit();
            }
            
            $cadena = $this->usuario_session->getCurrentUser();
            $this->cumplimiento->cite = strtoupper($_POST['txt_cite']);
            $this->cumplimiento->ci = $_POST['txt_ci'];
            $this->cumplimiento->nombre = strtoupper($_POST['txt_nombre']);
            $this->cumplimiento->idcatalogo = $_POST['SelItemCatalogo'];
            $this->cumplimiento->monto = $this->cumplimiento->obtener_precio_catalogo($_POST['SelItemCatalogo']);
            $this->cumplimiento->usuario = $cadena['nombre'];
            
            $id_insertado = $this->cumplimiento->add();
            if($id_insertado) {
                echo json_encode(['status' => 'success', 'idgarantia' => $id_insertado, 'message' => 'Garantía de Cumplimiento Registrada con Éxito']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al registrar']);
            }
        }
        exit();
    }

    public function edit() {
        if($_POST){
            if (empty($_POST['txt_idgarantia']) || empty($_POST['txt_cite']) || empty($_POST['txt_ci']) || empty($_POST['txt_nombre']) || empty($_POST['SelItemCatalogo']) || $_POST['SelItemCatalogo'] == '0') {
                echo json_encode(['status' => 'error', 'message' => 'Complete todos los campos obligatorios.']);
                exit();
            }
            
            $this->cumplimiento->idgarantia = $_POST['txt_idgarantia'];
            $this->cumplimiento->cite = strtoupper($_POST['txt_cite']);
            $this->cumplimiento->ci = $_POST['txt_ci'];
            $this->cumplimiento->nombre = strtoupper($_POST['txt_nombre']);
            $this->cumplimiento->idcatalogo = $_POST['SelItemCatalogo'];
            $this->cumplimiento->monto = $this->cumplimiento->obtener_precio_catalogo($_POST['SelItemCatalogo']);
            
            if($this->cumplimiento->edit()) {
                echo json_encode(['status' => 'success', 'message' => 'Garantía Modificada con Éxito']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al modificar el registro']);
            }
        }
        exit();
    }

    public function comprobante_ingreso() {
        if (!isset($_POST['idgarantia'])) {
            die("Error: ID de recibo no especificado.");
        }
        $id = $_POST['idgarantia'];

        $this->cumplimiento->idgarantia = $id;
        $datos = $this->cumplimiento->obtener_datos_recibo();

        if (!$datos) die("Error: Garantía no encontrada.");

        require_once ROOT . "libs/fpdf/fpdf.php";
        $pdf = new \FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);

        $this->dibujar_recibo($pdf, $datos, 10, 'ORIGINAL - CAJA');
        $pdf->SetDrawColor(150, 150, 150);
        for($i = 10; $i < 200; $i += 5) { $pdf->Line($i, 135, $i+2, 135); }
        $this->dibujar_recibo($pdf, $datos, 145, 'COPIA - INTERESADO');

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

    private function dibujar_recibo($pdf, $datos, $y, $copia) {
        $img_izq = ROOT . 'img/logos/logo_1.jpg'; 
        $img_cen = ROOT . 'img/logos/logo_2.jpg'; 
        $img_der = ROOT . 'img/logos/logo_3.jpg'; 
        if (file_exists($img_izq)) $pdf->Image($img_izq, 15, $y, 35);
        if (file_exists($img_cen)) $pdf->Image($img_cen, 75, $y, 60);
        if (file_exists($img_der)) $pdf->Image($img_der, 175, $y, 20);
        $pdf->SetFont('Arial', 'B', 10); $pdf->SetXY(45, $y + 11); $pdf->Cell(120, 5, utf8_decode('COMPROBANTE DE INGRESO - GARANTÍA DE CUMPLIMIENTO'), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 8); $pdf->SetXY(160, $y + 25); $pdf->Cell(40, 5, utf8_decode($copia), 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 10); $pdf->SetXY(15, $y + 30); $pdf->Cell(90, 6, utf8_decode('COMPROBANTE NRO: ' . str_pad($datos['IDGARANTIA'], 6, '0', STR_PAD_LEFT)), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10); $pdf->Cell(90, 6, utf8_decode('Fecha y Hora: ' . date('d/m/Y H:i', strtotime($datos['FECHA_COBRO']))), 0, 1, 'R'); $pdf->Ln(5);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Adjudicado:'), 1, 0, 'L', true); $pdf->SetFont('Arial', '', 9); $pdf->Cell(95, 7, utf8_decode(' ' . $datos['NOMBRE_POSTULANTE']), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 7, utf8_decode(' CI:'), 1, 0, 'L', true); $pdf->SetFont('Arial', '', 9); $pdf->Cell(35, 7, utf8_decode(' ' . $datos['CI_POSTULANTE']), 1, 1, 'L');
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Ítem Ganado:'), 1, 0, 'L', true); $pdf->SetFont('Arial', '', 9); $pdf->Cell(150, 7, utf8_decode(' ' . $datos['REFERENCIA'] . ' / ' . $datos['UBICACION'] . ' - ' . $datos['ITEM']), 1, 1, 'L');
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' CITE Carta Adj.:'), 1, 0, 'L', true); $pdf->SetFont('Arial', '', 9); $pdf->Cell(150, 7, utf8_decode(' ' . $datos['CITE_ADJUDICACION']), 1, 1, 'L'); $pdf->Ln(5);
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 11); $pdf->Cell(140, 10, utf8_decode(' IMPORTE TOTAL (Bs.):'), 1, 0, 'R', true); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(45, 10, number_format($datos['MONTO'], 2), 1, 1, 'C'); $pdf->Ln(15);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(30); $pdf->Cell(60, 4, '_____________________________', 0, 0, 'C'); $pdf->SetX(120); $pdf->Cell(60, 4, '_____________________________', 0, 1, 'C');
        $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Firma de Caja'), 0, 0, 'C'); $pdf->SetX(120); $pdf->Cell(60, 4, utf8_decode('Firma del Adjudicado'), 0, 1, 'C');
        $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Usuario: ' . $datos['USUARIO']), 0, 0, 'C');
    }
}
?>