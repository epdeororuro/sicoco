<?php namespace Models;

class Historial {
    private $con;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function lst_clientes() {
        // Trae a todos los clientes que tienen al menos un historial registrado
        $sql = "SELECT c.IDCLIENTE, c.CEDULA, c.NOMBRE_COMPLETO AS CLIENTE, c.CONTACTOS, c.DIRECCION,
                       (SELECT COUNT(*) FROM arriendos a WHERE a.IDCLIENTE = c.IDCLIENTE) AS TOTAL_CONTRATOS
                FROM clientes c
                HAVING TOTAL_CONTRATOS > 0
                ORDER BY c.NOMBRE_COMPLETO ASC";
        
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtener_kardex($idcliente) {
        // Consulta Maestra: Cruza Arriendos, Catálogo y Pagos para armar la línea de tiempo del cliente
        $sql = "SELECT a.IDARRIENDO, a.CONTRATO, a.FECHA_INICIO, a.ACTIVIDAD, a.VIGENTE,
                       c.DESCRIPCION AS ESPACIO, au.REFERENCIA, au.UBICACION,
                       (SELECT COUNT(*) FROM pagos p WHERE p.IDARRIENDO = a.IDARRIENDO AND p.PENDIENTE = 'SI') AS MESES_MORA,
                       (SELECT COUNT(*) FROM pagos p WHERE p.IDARRIENDO = a.IDARRIENDO AND p.PENDIENTE = 'NO') AS MESES_PAGADOS,
                       (SELECT COUNT(*) FROM pagos p WHERE p.IDARRIENDO = a.IDARRIENDO) AS TOTAL_MESES
                FROM arriendos a
                LEFT JOIN detalle d ON a.IDARRIENDO = d.IDARRIENDO
                LEFT JOIN catalogo c ON d.IDCATALOGO = c.IDCATALOGO
                LEFT JOIN areaubicacion au ON c.IDAREA = au.IDAREA
                WHERE a.IDCLIENTE = ?
                ORDER BY a.FECHA_INICIO DESC";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$idcliente]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>