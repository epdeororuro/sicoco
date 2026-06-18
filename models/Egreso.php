<?php namespace Models;

class Egreso {
    private $con;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function listar_egresos($inicio, $fin, $tipo) {
        $sql_propuesta = "SELECT 'Garantía de Propuesta' AS TIPO, IDPROPUESTA AS NRO, NOMBRE_POSTULANTE AS CLIENTE, CI_POSTULANTE AS CI, MONTO, FECHA_DEVOLUCION AS FECHA FROM garantias_propuesta WHERE ESTADO = 'DEVUELTA' AND DATE(FECHA_DEVOLUCION) BETWEEN ? AND ?";
        
        $sql_cumplimiento = "SELECT 'Garantía de Cumplimiento' AS TIPO, IDGARANTIA AS NRO, NOMBRE_POSTULANTE AS CLIENTE, CI_POSTULANTE AS CI, MONTO, FECHA_DEVOLUCION AS FECHA FROM garantias_cumplimiento WHERE ESTADO = 'DEVUELTA' AND DATE(FECHA_DEVOLUCION) BETWEEN ? AND ?";

        if ($tipo === 'PROPUESTA') {
            $stmt = $this->con->conexion->prepare($sql_propuesta . " ORDER BY FECHA DESC");
            $stmt->execute([$inicio, $fin]);
        } else if ($tipo === 'CUMPLIMIENTO') {
            $stmt = $this->con->conexion->prepare($sql_cumplimiento . " ORDER BY FECHA DESC");
            $stmt->execute([$inicio, $fin]);
        } else {
            $sql_completo = $sql_propuesta . " UNION ALL " . $sql_cumplimiento . " ORDER BY FECHA DESC";
            $stmt = $this->con->conexion->prepare($sql_completo);
            $stmt->execute([$inicio, $fin, $inicio, $fin]);
        }
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>