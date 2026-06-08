<?php namespace Models;
	
class Dashboard {

    private $con;

    public function __construct(){
        $this->con = new Conexion();
    }

    public function get_ingresos_mensuales() {
        $anio = date('Y');
        // Agrupamos la recaudación por mes, tomando como base la FECHA_PAGO real de Caja.
        $sql = "SELECT MONTH(FECHA_PAGO) AS mes, SUM(MONTO) as total 
                FROM pagos 
                WHERE PENDIENTE != 'SI' AND YEAR(FECHA_PAGO) = ? 
                GROUP BY MONTH(FECHA_PAGO) 
                ORDER BY mes ASC";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$anio]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get_estado_espacios() {
        $sql = "SELECT ESTADO, COUNT(*) as cantidad FROM catalogo GROUP BY ESTADO";
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>