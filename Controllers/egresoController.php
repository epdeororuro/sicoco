<?php
use Models\Egreso as Egreso;
use Config\sessionController as SessionController;

class egresoController {
    private $egreso;
    private $usuario_session;

    public function __construct() {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->egreso = new Egreso();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index() {}

    public function listar() {
        try {
            $inicio = isset($_POST['inicio']) ? $_POST['inicio'] : date('Y-m-01');
            $fin = isset($_POST['fin']) ? $_POST['fin'] : date('Y-m-d');
            $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'TODOS';

            $datos = $this->egreso->listar_egresos($inicio, $fin, $tipo);
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'success', 'data' => $datos]);
        } catch (\Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }
}
?>