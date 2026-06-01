<?php
use Models\Propuestas as Propuestas;
use Config\sessionController as SessionController;

class propuestasController {
    private $propuestas;
    private $usuario_session;

    public function __construct() {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->propuestas = new Propuestas();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index() {}

    public function listar() {
        $datos = $this->propuestas->lst();
        echo json_encode(['data' => $datos]);
        exit();
    }

    public function listar_areas() {
        $datos = $this->propuestas->lst_areas();
        echo json_encode(['data' => $datos]);
        exit();
    }

    public function listar_catalogo_por_area($idarea = null) {
        if(!$idarea){ echo json_encode(['data' => []]); exit(); }
        $datos = $this->propuestas->lst_catalogo_por_area($idarea);
        echo json_encode(['data' => $datos]);
        exit();
    }

    public function add() {
        if($_POST){
            if (empty($_POST['txt_ci']) || empty($_POST['txt_nombre']) || empty($_POST['SelItemCatalogo']) || $_POST['SelItemCatalogo'] == '0') {
                echo json_encode(['status' => 'error', 'message' => 'Complete todos los campos.']);
                exit();
            }
            
            $cadena = $this->usuario_session->getCurrentUser();
            $this->propuestas->ci = $_POST['txt_ci'];
            $this->propuestas->nombre = strtoupper($_POST['txt_nombre']);
            $this->propuestas->idcatalogo = $_POST['SelItemCatalogo'];
            $this->propuestas->monto = 100.00; // Monto Fijo por Reglamento
            $this->propuestas->usuario = $cadena['nombre'];
            
            if($this->propuestas->add()) {
                echo json_encode(['status' => 'success', 'message' => 'Garantía Registrada con Éxito']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al registrar']);
            }
        }
        exit();
    }

    public function devolver($id) {
        $this->propuestas->idpropuesta = $id;
        if($this->propuestas->devolver()) {
            echo json_encode(['status' => 'success', 'message' => 'Garantía Devuelta']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error en BD']);
        }
        exit();
    }

    public function ejecutar($id) {
        $this->propuestas->idpropuesta = $id;
        if($this->propuestas->ejecutar()) {
            echo json_encode(['status' => 'success', 'message' => 'Garantía Ejecutada a favor de la Empresa']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error en BD']);
        }
        exit();
    }

    public function comprobante_ingreso($id) {
        if ($id === 'ultimo') {
            $id = $this->propuestas->obtener_ultimo_id();
        }

        $this->propuestas->idpropuesta = $id;
        $datos = $this->propuestas->obtener_datos_recibo();

        if (!$datos) die("Error: Propuesta no encontrada.");

        $this->generar_pdf($datos, 'INGRESO');
    }

    public function comprobante_egreso($id) {
        $this->propuestas->idpropuesta = $id;
        $datos = $this->propuestas->obtener_datos_recibo();

        if (!$datos) die("Error: Propuesta no encontrada.");
        
        // REGLA DE SEGURIDAD BACKEND: Jamás confiar en la URL
        if ($datos['ESTADO'] !== 'DEVUELTA') {
            die("ACCESO DENEGADO: Violación de seguridad. La garantía no se encuentra en estado de devolución.");
        }

        $this->generar_pdf($datos, 'EGRESO');
    }

    private function generar_pdf($datos, $tipo) {
        require_once ROOT . "libs/fpdf/fpdf.php";
        $pdf = new \FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);

        $this->dibujar_recibo($pdf, $datos, 10, 'ORIGINAL - CAJA', $tipo);
        
        $pdf->SetDrawColor(150, 150, 150);
        for($i = 10; $i < 200; $i += 5) { 
            $pdf->Line($i, 135, $i+2, 135); 
        }

        $this->dibujar_recibo($pdf, $datos, 145, 'COPIA - INTERESADO', $tipo);

        if (ob_get_length()) ob_clean();
        $pdf->Output('I', 'Comprobante_'.$tipo.'_Garantia_'.$datos['IDPROPUESTA'].'.pdf');
        exit();
    }

    private function dibujar_recibo($pdf, $datos, $y, $copia, $tipo) {
        $img_izq = ROOT . 'img/logos/logo_1.jpg'; 
        $img_cen = ROOT . 'img/logos/logo_2.jpg'; 
        $img_der = ROOT . 'img/logos/logo_3.jpg'; 

        if (file_exists($img_izq)) $pdf->Image($img_izq, 15, $y, 35);
        if (file_exists($img_cen)) $pdf->Image($img_cen, 75, $y, 60);
        if (file_exists($img_der)) $pdf->Image($img_der, 175, $y, 20);

        $titulo = ($tipo == 'INGRESO') ? 'COMPROBANTE DE INGRESO - GARANTÍA DE PROPUESTA' : 'COMPROBANTE DE EGRESO - DEVOLUCIÓN DE GARANTÍA';
        $fecha_texto = ($tipo == 'INGRESO') ? $datos['FECHA_COBRO'] : $datos['FECHA_DEVOLUCION'];

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(45, $y + 11);
        $pdf->Cell(120, 5, utf8_decode($titulo), 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(160, $y + 25);
        $pdf->Cell(40, 5, utf8_decode($copia), 0, 1, 'R');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(15, $y + 30);
        $pdf->Cell(90, 6, utf8_decode('COMPROBANTE NRO: ' . str_pad($datos['IDPROPUESTA'], 6, '0', STR_PAD_LEFT)), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(90, 6, utf8_decode('Fecha y Hora: ' . date('d/m/Y H:i', strtotime($fecha_texto))), 0, 1, 'R');
        $pdf->Ln(5);

        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Postulante:'), 1, 0, 'L', true);
        $pdf->SetFont('Arial', '', 9); $pdf->Cell(95, 7, utf8_decode(' ' . $datos['NOMBRE_POSTULANTE']), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 7, utf8_decode(' CI:'), 1, 0, 'L', true);
        $pdf->SetFont('Arial', '', 9); $pdf->Cell(35, 7, utf8_decode(' ' . $datos['CI_POSTULANTE']), 1, 1, 'L');

        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Postula al Ítem:'), 1, 0, 'L', true);
        $pdf->SetFont('Arial', '', 9); $pdf->Cell(150, 7, utf8_decode(' ' . $datos['REFERENCIA'] . ' / ' . $datos['UBICACION'] . ' - ' . $datos['ITEM']), 1, 1, 'L');
        
        $pdf->Ln(5);
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 11); $pdf->Cell(140, 10, utf8_decode(' IMPORTE TOTAL (Bs.):'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(45, 10, number_format($datos['MONTO'], 2), 1, 1, 'C');
        $pdf->Ln(15);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(30); $pdf->Cell(60, 4, '_____________________________', 0, 0, 'C');
        $pdf->SetX(120); $pdf->Cell(60, 4, '_____________________________', 0, 1, 'C');
        $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Firma de Caja'), 0, 0, 'C');
        $pdf->SetX(120); $pdf->Cell(60, 4, utf8_decode('Firma del Postulante'), 0, 1, 'C');
        $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Usuario: ' . $datos['USUARIO']), 0, 0, 'C');
    }
}
?>