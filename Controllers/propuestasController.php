<?php
use Models\Propuesta as Propuesta;
use Models\Contrato as Contrato; // Reutilizamos su modelo para traer las áreas y el catálogo
use Config\sessionController as SessionController;

class propuestasController
{
    private $propuesta;
    private $contrato;
    private $usuario_session;

    public function __construct()
    {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->propuesta = new Propuesta();
            $this->contrato = new Contrato();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index() { }

    public function listar()
    {
        try {
            $datos = $this->propuesta->lst();
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'success', 'data' => $datos]);
        } catch (\Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
        }
        exit();
    }

    // Reutilizamos métodos del modelo contrato para llenar los selects del Modal
    public function listar_areas() {
        echo json_encode(['status' => 'success', 'data' => $this->contrato->lst_areas()]);
        exit();
    }
    public function listar_catalogo_por_area($idarea = null) {
        $this->contrato->set("idarea", $idarea);
        echo json_encode(['status' => 'success', 'data' => $this->contrato->lst_catalogo_por_area()]);
        exit();
    }

    public function add()
    {
        if($_POST) {
            $cadena = $this->usuario_session->getCurrentUser();
            $usr = isset($cadena['nombre']) ? $cadena['nombre'] : (isset($cadena['USR']) ? $cadena['USR'] : 'Sistema');
            $this->propuesta->set("usuario", $usr);
            $this->propuesta->set("ci_postulante", $_POST['txt_ci']);
            $this->propuesta->set("nombre_postulante", $_POST['txt_nombre']);
            $this->propuesta->set("idcatalogo", $_POST['SelItemCatalogo']);
            
            $id_insertado = $this->propuesta->add();
            if (ob_get_length()) ob_clean(); // Limpiar buffers para asegurar JSON puro
            if ($id_insertado) {
                echo json_encode(['status' => 'success', 'idpropuesta' => $id_insertado, 'message' => 'Garantía cobrada con éxito']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al registrar la garantía en la BD']);
            }
        }
        exit();
    }

    public function edit()
    {
        if($_POST) {
            $this->propuesta->set("idpropuesta", $_POST['txt_idpropuesta']);
            $this->propuesta->set("ci_postulante", $_POST['txt_ci']);
            $this->propuesta->set("nombre_postulante", $_POST['txt_nombre']);
            $this->propuesta->set("idcatalogo", $_POST['SelItemCatalogo']);
            
            if ($this->propuesta->edit()) {
                if (ob_get_length()) ob_clean();
                echo json_encode(['status' => 'success', 'message' => 'Garantía modificada con éxito']);
            } else {
                if (ob_get_length()) ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al modificar la garantía en la BD']);
            }
        }
        exit();
    }

    public function devolver($argumento)
    {
        $this->propuesta->set("idpropuesta", $argumento);
        if (ob_get_length()) ob_clean(); // Limpiar buffers para asegurar JSON puro
        if ($this->propuesta->devolver()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar la devolución']);
        }
        exit();
    }

    public function imprimir_recibo($idpropuesta)
    {
        $this->propuesta->set("idpropuesta", $idpropuesta);
        $datos = $this->propuesta->obtener_recibo();

        if (!$datos || count($datos) == 0) { die("Error: Recibo no encontrado."); }
        $d = $datos[0];

        require_once ROOT . "libs/fpdf/fpdf.php";
        $pdf = new \FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);

        $this->dibujar_recibo($pdf, $d, 10, 'ORIGINAL - CLIENTE');
        
        $pdf->SetDrawColor(150, 150, 150);
        for($i = 10; $i < 200; $i += 5) { $pdf->Line($i, 135, $i+2, 135); }

        $this->dibujar_recibo($pdf, $d, 145, 'COPIA - ARCHIVO');

        if (ob_get_length()) ob_clean();
        $pdf->Output('I', 'Garantia_Propuesta_Nro_'.$idpropuesta.'.pdf');
        exit();
    }

    private function dibujar_recibo($pdf, $datos, $y, $tipo)
    {
        // (Simplificado) El mismo formato corporativo que en Pagos
        $pdf->SetFont('Arial', 'B', 14); $pdf->SetXY(45, $y + 5); $pdf->Cell(120, 6, utf8_decode(''), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 10); $pdf->SetXY(45, $y + 11); $pdf->Cell(120, 5, utf8_decode('GARANTÍA DE SERIEDAD DE PROPUESTA'), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 10); $pdf->SetXY(15, $y + 30); $pdf->Cell(90, 6, utf8_decode('RECIBO NRO: ' . str_pad($datos['IDPROPUESTA'], 6, '0', STR_PAD_LEFT)), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10); $pdf->Cell(90, 6, utf8_decode('Fecha y Hora: ' . date('d/m/Y H:i', strtotime($datos['FECHA_COBRO']))), 0, 1, 'R');
        $pdf->Ln(2); $pdf->SetFillColor(240, 240, 240); $pdf->SetDrawColor(200, 200, 200);
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Postulante:'), 1, 0, 'L', true); $pdf->SetFont('Arial', '', 9); $pdf->Cell(95, 7, utf8_decode(' ' . $datos['NOMBRE_POSTULANTE']), 1, 0, 'L'); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 7, utf8_decode(' CI:'), 1, 0, 'L', true); $pdf->SetFont('Arial', '', 9); $pdf->Cell(35, 7, utf8_decode(' ' . $datos['CI_POSTULANTE']), 1, 1, 'L');
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Ítem Postulado:'), 1, 0, 'L', true); $pdf->SetFont('Arial', '', 8); $pdf->Cell(150, 7, utf8_decode(' ' . substr($datos['ESPACIO'], 0, 80)), 1, 1, 'L'); $pdf->Ln(5);
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 11); $pdf->Cell(140, 10, utf8_decode(' TOTAL RETENIDO:'), 1, 0, 'R', true); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(45, 10, utf8_decode('Bs. ' . number_format($datos['MONTO'], 2)), 1, 1, 'C'); $pdf->Ln(15);
        $pdf->SetFont('Arial', '', 9); $pdf->SetX(30); $pdf->Cell(60, 4, '_____________________________', 0, 0, 'C'); $pdf->SetX(120); $pdf->Cell(60, 4, '_____________________________', 0, 1, 'C'); $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Firma del Cajero'), 0, 0, 'C'); $pdf->SetX(120); $pdf->Cell(60, 4, utf8_decode('Firma del Postulante'), 0, 1, 'C'); $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Cajero: ' . $datos['USUARIO']), 0, 0, 'C');
    }
}
?>