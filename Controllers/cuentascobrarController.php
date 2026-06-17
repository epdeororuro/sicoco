<?php
use Models\Cuentascobrar as Cuentascobrar;
use Config\sessionController as SessionController;

class cuentascobrarController {
    private $cuentascobrar;
    private $usuario_session;

    public function __construct() {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->cuentascobrar = new Cuentascobrar();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index() {
        // La vista se carga mediante el enrutador principal de SICOCO
    }

    public function listar() {
        try {
            $datos = $this->cuentascobrar->lst_deudores();
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