<?php namespace Models;

class Cuentascobrar {
    private $con;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function lst_deudores() {
        $sql = "SELECT a.IDARRIENDO, a.CONTRATO, a.FECHA_INICIO, a.ACTIVIDAD,
                       c.CEDULA, c.NOMBRE_COMPLETO AS CLIENTE, c.CONTACTOS,
                       (SELECT COUNT(*) FROM pagos p WHERE p.IDARRIENDO = a.IDARRIENDO AND p.PENDIENTE = 'SI') AS MESES_MORA,
                       (SELECT SUM(MONTO) FROM pagos p WHERE p.IDARRIENDO = a.IDARRIENDO AND p.PENDIENTE = 'SI') AS DEUDA_TOTAL
                FROM arriendos a
                INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
                WHERE a.VIGENTE = 'CXC'
                ORDER BY c.NOMBRE_COMPLETO ASC";
        
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>