<?php
use Models\Inicio as Inicio;
use Config\sessionController as SessionController;

class inicioController
{
    private $inicio;
    private $usuario_session;

    public function __construct()
    {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->inicio = new Inicio();
        } else {
            header('Location:'. URL . "login");
            exit();
        }
    }
    
    public function index()
    {
        // El renderizado de la vista views/inicio/index.php lo maneja el enrutador principal
    }

    public function cargar_kpis()
    {
        try {
            $datos = $this->inicio->get_kpis();
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'success', 'data' => $datos]);
        } catch (\Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }

    public function cargar_grafico()
    {
        try {
            $anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
            $datos = $this->inicio->get_chart_ingresos($anio);
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'success', 'data' => $datos]);
        } catch (\Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }

    public function cargar_anios()
    {
        try {
            $datos = $this->inicio->get_anios_pagos();
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'success', 'data' => $datos]);
        } catch (\Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }

    public function cargar_grafico_espacios()
    {
        try {
            $datos = $this->inicio->get_estado_espacios();
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