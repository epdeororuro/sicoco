<?php namespace Models;

class Reportes {
    private $con;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function listar_logs_cierres() {
        $sql = "SELECT IDLOGCIERRE, FECHA_INICIO, FECHA_FIN, FECHA_GENERACION, USUARIO FROM log_cierres ORDER BY FECHA_GENERACION DESC";
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>