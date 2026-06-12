<?php
use Models\Propuesta as Propuesta;
use Config\sessionController as SessionController;

class propuestaController
{
    private $propuesta;
    private $usuario_session;

    public function __construct()
    {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->propuesta = new Propuesta();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index() { }

    public function listar()
    {
        $datos = $this->propuesta->lst();
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['data' => $datos]);
        exit();
    }

    public function listar_areas() {
        $datos = $this->propuesta->lst_areas();
        if (ob_get_length()) ob_clean();
        echo json_encode(['data' => $datos]);
        exit();
    }

    public function listar_catalogo_por_area($idarea = null) {
        if(!$idarea){ echo json_encode(['data' => []]); exit(); }
        $datos = $this->propuesta->lst_catalogo_por_area($idarea);
        if (ob_get_length()) ob_clean();
        echo json_encode(['data' => $datos]);
        exit();
    }

    public function add()
    {
        if($_POST) {
            $cadena = $this->usuario_session->getCurrentUser();
            $usr = isset($cadena['nombre']) ? $cadena['nombre'] : 'Sistema';
            
            $this->propuesta->set("usuario", $usr);
            $this->propuesta->set("ci_postulante", $_POST['txt_ci']);
            $this->propuesta->set("nombre_postulante", $_POST['txt_nombre']);
            $this->propuesta->set("idcatalogo", $_POST['SelItemCatalogo']);
            
            $id_insertado = $this->propuesta->add();
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json');
            if ($id_insertado) {
                echo json_encode(['status' => 'success', 'idpropuesta' => $id_insertado, 'message' => 'Garantía cobrada con éxito']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al registrar la garantía.']);
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
            
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json');
            if ($this->propuesta->edit()) {
                echo json_encode(['status' => 'success', 'message' => 'Garantía modificada con éxito']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al modificar la garantía.']);
            }
        }
        exit();
    }

    public function devolver($argumento)
    {
        $this->propuesta->set("idpropuesta", $argumento);
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        if ($this->propuesta->devolver()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar la devolución.']);
        }
        exit();
    }

    public function imprimir_recibo()
    {
        if (!isset($_POST['idpropuesta'])) {
            die("Error: ID de recibo no especificado.");
        }
        $idpropuesta = $_POST['idpropuesta'];

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

        $this->dibujar_recibo($pdf, $d, 145, 'COPIA - CAJA EPDEOR');

        if (ob_get_length()) ob_clean();
        
        // Envolver el PDF en HTML para forzar el Título de pestaña y el Favicon
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
        $img_izq = ROOT . 'img/logos/logo_1.jpg'; 
        $img_cen = ROOT . 'img/logos/logo_2.jpg'; 
        $img_der = ROOT . 'img/logos/logo_3.jpg'; 
        if (file_exists($img_izq)) $pdf->Image($img_izq, 15, $y, 35);
        if (file_exists($img_cen)) $pdf->Image($img_cen, 75, $y, 60);
        if (file_exists($img_der)) $pdf->Image($img_der, 175, $y, 20);

        $es_devolucion = ($datos['ESTADO'] === 'DEVUELTA');
        $titulo = $es_devolucion ? 'COMPROBANTE DE EGRESO - GARANTÍA DE PROPUESTA' : 'COMPROBANTE DE INGRESO - GARANTÍA DE PROPUESTA';
        $fecha_mostrar = ($es_devolucion && !empty($datos['FECHA_DEVOLUCION'])) ? $datos['FECHA_DEVOLUCION'] : $datos['FECHA_COBRO'];

        $pdf->SetFont('Arial', 'B', 10); $pdf->SetXY(45, $y + 11); $pdf->Cell(120, 5, utf8_decode($titulo), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 8); $pdf->SetXY(160, $y + 25); $pdf->Cell(40, 5, utf8_decode($tipo), 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 10); $pdf->SetXY(15, $y + 30); $pdf->Cell(90, 6, utf8_decode('COMPROBANTE NRO: ' . str_pad($datos['IDPROPUESTA'], 6, '0', STR_PAD_LEFT)), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10); $pdf->Cell(90, 6, utf8_decode('Fecha y Hora: ' . date('d/m/Y H:i', strtotime($fecha_mostrar))), 0, 1, 'R'); $pdf->Ln(5);
        
        $pdf->SetFillColor(240, 240, 240); $pdf->SetDrawColor(200, 200, 200);
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Postulante:'), 1, 0, 'L', true); $pdf->SetFont('Arial', '', 9); $pdf->Cell(95, 7, utf8_decode(' ' . $datos['NOMBRE_POSTULANTE']), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(20, 7, utf8_decode(' CI:'), 1, 0, 'L', true); $pdf->SetFont('Arial', '', 9); $pdf->Cell(35, 7, utf8_decode(' ' . $datos['CI_POSTULANTE']), 1, 1, 'L');
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(35, 7, utf8_decode(' Ítem Postulado:'), 1, 0, 'L', true); $pdf->SetFont('Arial', '', 8); $pdf->Cell(150, 7, utf8_decode(' ' . substr($datos['ESPACIO'], 0, 80)), 1, 1, 'L'); $pdf->Ln(5);
        
        $pdf->SetX(15); $pdf->SetFont('Arial', 'B', 11); $pdf->Cell(140, 10, utf8_decode(' IMPORTE TOTAL (Bs.):'), 1, 0, 'R', true); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(45, 10, number_format($datos['MONTO'], 2), 1, 1, 'C'); $pdf->Ln(15);
        
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(30); $pdf->Cell(60, 4, '_____________________________', 0, 0, 'C'); $pdf->SetX(120); $pdf->Cell(60, 4, '_____________________________', 0, 1, 'C');
        $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Firma de Caja'), 0, 0, 'C'); $pdf->SetX(120); $pdf->Cell(60, 4, utf8_decode('Firma del Postulante'), 0, 1, 'C');
        $pdf->SetX(30); $pdf->Cell(60, 4, utf8_decode('Usuario: ' . $datos['USUARIO']), 0, 0, 'C');
    }
}