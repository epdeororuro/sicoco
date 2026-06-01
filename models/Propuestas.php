<?php namespace Models;

class Propuestas {
    private $con;
    public $idpropuesta;
    public $ci;
    public $nombre;
    public $idcatalogo;
    public $monto;
    public $usuario;

    public function __construct(){ 
        $this->con = new Conexion(); 
    }

    public function lst(){
        $sql = "SELECT p.*, c.DESCRIPCION as ITEM, a.REFERENCIA, a.UBICACION 
                FROM propuestas p 
                INNER JOIN catalogo c ON p.IDCATALOGO = c.IDCATALOGO 
                INNER JOIN areaubicacion a ON c.IDAREA = a.IDAREA 
                ORDER BY p.IDPROPUESTA DESC";
        return $this->con->conexion->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function add(){
        $sql = "INSERT INTO propuestas (CI_POSTULANTE, NOMBRE_POSTULANTE, IDCATALOGO, MONTO, USUARIO) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->con->conexion->prepare($sql);
        return $stmt->execute([$this->ci, $this->nombre, $this->idcatalogo, $this->monto, $this->usuario]);
    }

    public function devolver(){
        $sql = "UPDATE propuestas SET ESTADO = 'DEVUELTA', FECHA_DEVOLUCION = NOW() WHERE IDPROPUESTA = ?";
        return $this->con->conexion->prepare($sql)->execute([$this->idpropuesta]);
    }

    public function ejecutar(){
        $sql = "UPDATE propuestas SET ESTADO = 'EJECUTADA' WHERE IDPROPUESTA = ?";
        return $this->con->conexion->prepare($sql)->execute([$this->idpropuesta]);
    }

    public function lst_areas(){
        $sql = "SELECT IDAREA, DISTRIBUCION FROM v_areas ORDER BY DISTRIBUCION";
        return $this->con->conexion->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function lst_catalogo_por_area($idarea){
        $sql = "SELECT IDCATALOGO, CONCAT(DISTRIBUCION, ' - ', DESCRIPCION ) AS BESPACIO FROM v_catalogo WHERE ESTADO ='DISPONIBLE' AND IDAREA = ?";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$idarea]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function obtener_datos_recibo(){
        $sql = "SELECT p.*, c.DESCRIPCION as ITEM, a.REFERENCIA, a.UBICACION 
                FROM propuestas p 
                INNER JOIN catalogo c ON p.IDCATALOGO = c.IDCATALOGO 
                INNER JOIN areaubicacion a ON c.IDAREA = a.IDAREA 
                WHERE p.IDPROPUESTA = ?";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$this->idpropuesta]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function obtener_ultimo_id() {
        $sql = "SELECT MAX(IDPROPUESTA) as ultimo FROM propuestas";
        $resultado = $this->con->conexion->query($sql)->fetch(\PDO::FETCH_ASSOC);
        return $resultado['ultimo'];
    }
}
?>