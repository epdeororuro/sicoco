<?php
use Models\Estorno as Estorno;
use Config\sessionController as SessionController;

class estornoController {
    private $estorno;
    private $usuario_session;

    public function __construct() {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $currentUser = $this->usuario_session->getCurrentUser();
            if (!isset($currentUser['cargo']) || $currentUser['cargo'] != 1) {
                header('Location:'. URL . "inicio");
                exit();
            }
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
            
            $exito = $this->estorno->anular_recibo($nro, $motivo, $usr);
            if ($exito) {
                $this->usuario_session->registrarActividad('RECIBO_ESTORNADO', "Anuló el recibo Nro: $nro. Motivo: $motivo");
            }
            
            if (ob_get_length()) ob_clean();
            echo json_encode($exito ? ['status' => 'success', 'message' => 'Recibo anulado correctamente y deuda restaurada.'] : ['status' => 'error', 'message' => 'No se pudo anular el recibo. Quizás ya estaba anulado.']);
        }
        exit();
    }
}
?>