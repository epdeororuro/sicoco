<?php
use Models\Reportes as Reportes;
use Config\sessionController as SessionController;

class reportesController {
    private $reportes;
    private $usuario_session;

    public function __construct() {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->reportes = new Reportes();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index() {
        // No hace nada, pero permite que el enrutador cargue la vista views/reportes/index.php
    }

    public function listar_ingresos() {
        try {
            $inicio = isset($_POST['inicio']) ? $_POST['inicio'] : date('Y-m-01');
            $fin = isset($_POST['fin']) ? $_POST['fin'] : date('Y-m-d');

            $datos = $this->reportes->obtener_ingresos($inicio, $fin);
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'success', 'data' => $datos]);
        } catch (\Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
        }
        exit();
    }
}
?>