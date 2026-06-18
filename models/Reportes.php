<?php namespace Models;

class Reportes {
    private $con;

    public function __construct(){ 
        $this->con = new Conexion(); 
    }

    public function lst_logs(){
        $sql = "SELECT DATE_FORMAT(FECHA_INICIO, '%d/%m/%Y') as FECHA_INICIO, 
                       DATE_FORMAT(FECHA_FIN, '%d/%m/%Y') as FECHA_FIN, 
                       DATE_FORMAT(FECHA_GENERACION, '%d/%m/%Y %H:%i:%s') as FECHA_GENERACION, 
                       USUARIO 
                FROM log_cierres ORDER BY IDLOGCIERRE DESC";
        return $this->con->conexion->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}