<?php
use Models\Reportes as Reportes;
use Config\sessionController as SessionController;

class reportesController
{
    private $reportes;
    private $usuario_session;

    public function __construct()
    {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->reportes = new Reportes();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index()
    {
        // Enrutador cargará views/reportes/index.php automáticamente
    }

    public function listar_logs_cierres()
    {
        try {
            $datos = $this->reportes->listar_logs_cierres();
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