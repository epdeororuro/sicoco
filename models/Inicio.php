<?php namespace Models;

class Inicio {
    private $con;

    public function __construct(){
        $this->con = new Conexion();
    }

    public function get_kpis(){
        $sql = "SELECT 
                    (SELECT IFNULL(SUM(MONTO), 0) FROM pagos WHERE PENDIENTE='NO' AND DATE(FECHA_PAGO) = CURDATE()) AS INGRESOS_HOY,
                    (SELECT IFNULL(SUM(MONTO), 0) FROM pagos WHERE PENDIENTE='NO' AND MONTH(FECHA_PAGO) = MONTH(CURDATE()) AND YEAR(FECHA_PAGO) = YEAR(CURDATE())) AS INGRESOS_MES,
                    (SELECT COUNT(*) FROM arriendos WHERE VIGENTE='SI') AS CONTRATOS_VIGENTES,
                    (SELECT COUNT(*) FROM catalogo WHERE ESTADO='DISPONIBLE') AS ESPACIOS_DISPONIBLES
                ";
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function get_chart_ingresos(){
        // Trae los ingresos de los últimos 6 meses agrupados
        $sql = "SELECT DATE_FORMAT(FECHA_PAGO, '%Y-%m') as mes, SUM(MONTO) as total 
                FROM pagos 
                WHERE PENDIENTE='NO' 
                GROUP BY mes 
                ORDER BY mes DESC LIMIT 6";
        $stmt = $this->con->conexion->query($sql);
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_reverse($res); // Invertimos el orden para que el gráfico vaya de más antiguo a más reciente (Izquierda a Derecha)
    }

    public function get_estado_espacios(){
        $sql = "SELECT ESTADO, COUNT(*) as cantidad FROM catalogo GROUP BY ESTADO";
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>