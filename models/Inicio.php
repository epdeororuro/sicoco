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

    public function get_chart_ingresos($anio = null){
        if (!$anio) $anio = date('Y');
        $sql = "SELECT DATE_FORMAT(FECHA_PAGO, '%Y-%m') as mes, SUM(MONTO) as total 
                FROM pagos 
                WHERE PENDIENTE='NO' AND YEAR(FECHA_PAGO) = ?
                GROUP BY mes 
                ORDER BY mes ASC";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$anio]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get_anios_pagos(){
        $sql = "SELECT DISTINCT YEAR(FECHA_PAGO) as anio FROM pagos WHERE PENDIENTE='NO' AND FECHA_PAGO IS NOT NULL ORDER BY anio DESC";
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get_estado_espacios(){
        $sql = "SELECT ESTADO, COUNT(*) as cantidad FROM catalogo GROUP BY ESTADO";
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get_cxc_relacion(){
        $sql = "SELECT 
                    (SELECT IFNULL(SUM(MONTO), 0) FROM pagos WHERE PENDIENTE='NO' AND ESTADO_RECIBO='ACTIVO') AS COBRADO,
                    (SELECT IFNULL(SUM(MONTO), 0) FROM pagos WHERE PENDIENTE='SI' AND ESTADO_RECIBO='ACTIVO') AS PENDIENTE";
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function get_cxc_top_deudores(){
        $sql = "SELECT c.NOMBRE_COMPLETO AS CLIENTE, SUM(p.MONTO) AS DEUDA_TOTAL
                FROM pagos p
                INNER JOIN arriendos a ON p.IDARRIENDO = a.IDARRIENDO
                INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
                WHERE p.PENDIENTE = 'SI' AND p.ESTADO_RECIBO = 'ACTIVO'
                GROUP BY c.IDCLIENTE, c.NOMBRE_COMPLETO
                ORDER BY DEUDA_TOTAL DESC
                LIMIT 5";
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>