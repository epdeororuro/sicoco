<?php
use Models\Estorno as Estorno;
use Config\sessionController as SessionController;

class estornoController {
    private $estorno;
    private $usuario_session;

    public function __construct() {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->estorno = new Estorno();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index() {}

    public function buscar() {
        if(isset($_POST['nro_recibo'])) {
            $nro = str_pad($_POST['nro_recibo'], 6, '0', STR_PAD_LEFT);
            $datos = $this->estorno->buscar_recibo($nro);
            if (ob_get_length()) ob_clean();
            echo json_encode($datos ? ['status' => 'success', 'data' => $datos] : ['status' => 'error', 'message' => 'Recibo no encontrado.']);
        }
        exit();
    }

    public function anular() {
        if(isset($_POST['nro_recibo']) && isset($_POST['motivo'])) {
            $nro = str_pad($_POST['nro_recibo'], 6, '0', STR_PAD_LEFT);
            $motivo = strtoupper($_POST['motivo']);
            $cadena = $this->usuario_session->getCurrentUser();
            $usr = $cadena['nombre'];
            if (ob_get_length()) ob_clean();
            echo json_encode($this->estorno->anular_recibo($nro, $motivo, $usr) ? ['status' => 'success', 'message' => 'Recibo anulado correctamente y deuda restaurada.'] : ['status' => 'error', 'message' => 'No se pudo anular el recibo. Quizás ya estaba anulado.']);
        }
        exit();
    }
}
?>