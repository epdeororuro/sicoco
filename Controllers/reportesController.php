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

    public function listar_logs() {
        $datos = $this->reportes->lst_logs();
        if (ob_get_length()) ob_clean();
        echo json_encode(['data' => $datos]);
        exit();
    }
}
?>