<?php
use Models\Dashboard as Dashboard;
use Config\sessionController as SessionController;

class dashboardController
{
    private $dashboard;
    private $usuario_session;

    public function __construct()
    {
        $this->usuario_session = new SessionController();
        if ($this->usuario_session->verifica()) {
            $this->dashboard = new Dashboard();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
            exit();
        }
    }

    public function get_data()
    {
        try {
            $ingresos = $this->dashboard->get_ingresos_mensuales();
            $espacios = $this->dashboard->get_estado_espacios();

            // 1. Acomodar ingresos en un array de 12 meses (0 = Enero, 11 = Diciembre)
            $ingresos_array = array_fill(0, 12, 0); 
            foreach($ingresos as $ing) {
                $mes_index = intval($ing['mes']) - 1;
                $ingresos_array[$mes_index] = floatval($ing['total']);
            }

            // 2. Acomodar estados del catálogo
            $labels_espacios = [];
            $data_espacios = [];
            $colores_espacios = [];
            
            // Mapeo seguro para que siempre existan ambas variables en el gráfico
            $estado_map = ['ALQUILADO' => 0, 'DISPONIBLE' => 0];
            foreach($espacios as $esp) {
                $estado_map[$esp['ESTADO']] = intval($esp['cantidad']);
            }

            foreach($estado_map as $estado => $cantidad) {
                $labels_espacios[] = $estado;
                $data_espacios[] = $cantidad;
                // Verde para Alquilados, Azul/Celeste para Disponibles
                $colores_espacios[] = ($estado === 'ALQUILADO') ? '#28a745' : '#17a2b8';
            }

            echo json_encode([
                'status' => 'success',
                'ingresos' => $ingresos_array,
                'espacios' => [
                    'labels' => $labels_espacios,
                    'data' => $data_espacios,
                    'colores' => $colores_espacios
                ]
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }
}
?>