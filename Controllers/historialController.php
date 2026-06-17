<?php
use Models\Historial as Historial;
use Config\sessionController as SessionController;

class historialController {
    private $historial;
    private $usuario_session;

    public function __construct() {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->historial = new Historial();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index() {
        // El enrutador de SICOCO llamará a views/historial/index.php automáticamente
    }

    public function listar_clientes() {
        try {
            $datos = $this->historial->lst_clientes();
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'success', 'data' => $datos]);
        } catch (\Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }

    public function obtener_kardex($idcliente) {
        try {
            $datos = $this->historial->obtener_kardex($idcliente);
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