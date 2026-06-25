<?php namespace Models;

class Reportes {
    private $con;

    public function __construct(){ 
        $this->con = new Conexion(); 
    }

    public function obtener_ingresos($inicio, $fin) {
        $sql = "SELECT p.NRO_RECIBO, DATE_FORMAT(MAX(p.FECHA_PAGO), '%Y-%m-%d %H:%i') AS FECHA, 
                       SUM(p.MONTO) AS TOTAL, 'ACTIVO' AS ESTADO_RECIBO, '' AS MOTIVO_ANULACION,
                       GROUP_CONCAT(p.PERIODO ORDER BY p.PERIODO ASC SEPARATOR ', ') AS PERIODOS, 
                       c.NOMBRE_COMPLETO AS CLIENTE, a.CONTRATO, p.USR AS CAJERO
                FROM pagos p
                INNER JOIN arriendos a ON p.IDARRIENDO = a.IDARRIENDO
                INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
                WHERE p.PENDIENTE = 'NO' AND p.NRO_RECIBO IS NOT NULL AND DATE(p.FECHA_PAGO) BETWEEN ? AND ?
                GROUP BY p.NRO_RECIBO, c.NOMBRE_COMPLETO, a.CONTRATO, p.USR
                
                UNION ALL
                
                SELECT NRO_RECIBO, DATE_FORMAT(FECHA_COBRO, '%Y-%m-%d %H:%i') AS FECHA, MONTO_TOTAL AS TOTAL, 'ANULADO' AS ESTADO_RECIBO, MOTIVO AS MOTIVO_ANULACION,
                       PERIODOS_COBRADOS AS PERIODOS, CLIENTE, CONTRATO, CAJERO_ORIGINAL AS CAJERO
                FROM log_estornos
                WHERE DATE(FECHA_COBRO) BETWEEN ? AND ?
                
                ORDER BY FECHA DESC";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$inicio, $fin, $inicio, $fin]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}