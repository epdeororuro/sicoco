<?php namespace Models;

class Cumplimiento {
    private $con;
    public $idgarantia;
    public $cite;
    public $ci;
    public $nombre;
    public $idcatalogo;
    public $monto;
    public $usuario;

    public function __construct(){ 
        $this->con = new Conexion(); 
    }

    public function lst(){
        $sql = "SELECT g.*, c.DESCRIPCION as ITEM, a.REFERENCIA, a.UBICACION, 
                IFNULL(arr.CONTRATO, 'SIN CONTRATO') AS NRO_CONTRATO
                FROM garantias_cumplimiento g 
                INNER JOIN catalogo c ON g.IDCATALOGO = c.IDCATALOGO 
                INNER JOIN areaubicacion a ON c.IDAREA = a.IDAREA 
                LEFT JOIN arriendos arr ON g.IDARRIENDO = arr.IDARRIENDO
                ORDER BY g.IDGARANTIA DESC";
        return $this->con->conexion->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function add(){
        $sql = "INSERT INTO garantias_cumplimiento (CITE_ADJUDICACION, CI_POSTULANTE, NOMBRE_POSTULANTE, IDCATALOGO, MONTO, USUARIO) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->con->conexion->prepare($sql);
        return $stmt->execute([$this->cite, $this->ci, $this->nombre, $this->idcatalogo, $this->monto, $this->usuario]);
    }

    public function lst_areas(){
        $sql = "SELECT IDAREA, DISTRIBUCION FROM v_areas ORDER BY DISTRIBUCION";
        return $this->con->conexion->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function lst_catalogo_por_area($idarea){
        $sql = "SELECT IDCATALOGO, CONCAT(DISTRIBUCION, ' - ', DESCRIPCION ) AS BESPACIO, ALQUILER FROM v_catalogo WHERE ESTADO ='DISPONIBLE' AND IDAREA = ?";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$idarea]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function obtener_datos_recibo(){
        $sql = "SELECT g.*, c.DESCRIPCION as ITEM, a.REFERENCIA, a.UBICACION 
                FROM garantias_cumplimiento g 
                INNER JOIN catalogo c ON g.IDCATALOGO = c.IDCATALOGO 
                INNER JOIN areaubicacion a ON c.IDAREA = a.IDAREA 
                WHERE g.IDGARANTIA = ?";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$this->idgarantia]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function obtener_precio_catalogo($idcatalogo) {
        $sql = "SELECT ALQUILER FROM catalogo WHERE IDCATALOGO = ?";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$idcatalogo]);
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $res ? $res['ALQUILER'] : 0;
    }

    public function obtener_ultimo_id() {
        $sql = "SELECT MAX(IDGARANTIA) as ultimo FROM garantias_cumplimiento";
        $resultado = $this->con->conexion->query($sql)->fetch(\PDO::FETCH_ASSOC);
        return $resultado['ultimo'];
    }
}
?>