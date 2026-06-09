<?php namespace Models;

class Propuestas {
    private $con;
    public $idpropuesta;
    public $ci_postulante;
    public $nombre_postulante;
    public $idcatalogo;
    public $usuario;

    public function __construct(){ 
        $this->con = new Conexion(); 
    }

    public function set($atributo, $contenido){
        $this->$atributo = $contenido;
    }

    public function lst(){
        $sql = "SELECT p.*, a.IDAREA, CONCAT(a.REFERENCIA, ' - ', a.UBICACION, ' / ', c.DESCRIPCION) AS ESPACIO 
                FROM propuestas p 
                INNER JOIN catalogo c ON p.IDCATALOGO = c.IDCATALOGO 
                INNER JOIN areaubicacion a ON c.IDAREA = a.IDAREA 
                ORDER BY p.IDPROPUESTA DESC";
        return $this->con->conexion->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function add(){
        $sql_id = "SELECT COALESCE(MAX(IDPROPUESTA), 0) + 1 FROM propuestas";
        $stmt_id = $this->con->conexion->query($sql_id);
        $next_id = $stmt_id->fetchColumn();

        $sql = "INSERT INTO propuestas (IDPROPUESTA, CI_POSTULANTE, NOMBRE_POSTULANTE, IDCATALOGO, MONTO, USUARIO) 
                VALUES (?, ?, UPPER(?), ?, 100.00, ?)";
        $stmt = $this->con->conexion->prepare($sql);
        if ($stmt->execute([$next_id, $this->ci_postulante, $this->nombre_postulante, $this->idcatalogo, $this->usuario])) {
            return $next_id; 
        }
        return false;
    }

    public function edit(){
        $sql = "UPDATE propuestas SET CI_POSTULANTE = ?, NOMBRE_POSTULANTE = UPPER(?), IDCATALOGO = ? WHERE IDPROPUESTA = ?";
        $stmt = $this->con->conexion->prepare($sql);
        return $stmt->execute([$this->ci_postulante, $this->nombre_postulante, $this->idcatalogo, $this->idpropuesta]);
    }

    public function devolver(){
        $sql = "UPDATE propuestas SET ESTADO = 'DEVUELTA', FECHA_DEVOLUCION = CURRENT_TIMESTAMP WHERE IDPROPUESTA = ?";
        $stmt = $this->con->conexion->prepare($sql);
        return $stmt->execute([$this->idpropuesta]);
    }

    public function lst_areas(){
        $sql = "SELECT IDAREA, DISTRIBUCION FROM v_areas ORDER BY DISTRIBUCION";
        return $this->con->conexion->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function lst_catalogo_por_area($idarea){
        $sql = "SELECT IDCATALOGO, DESCRIPCION AS BESPACIO, ALQUILER FROM v_catalogo WHERE ESTADO ='DISPONIBLE' AND IDAREA = ?";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$idarea]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function obtener_recibo(){
        $sql = "SELECT p.*, CONCAT(a.REFERENCIA, ' - ', a.UBICACION, ' / ', c.DESCRIPCION) AS ESPACIO 
                FROM propuestas p 
                INNER JOIN catalogo c ON p.IDCATALOGO = c.IDCATALOGO 
                INNER JOIN areaubicacion a ON c.IDAREA = a.IDAREA 
                WHERE p.IDPROPUESTA = ?";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$this->idpropuesta]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtener_ultimo_id() {
        $sql = "SELECT MAX(IDPROPUESTA) as ultimo FROM propuestas";
        $resultado = $this->con->conexion->query($sql)->fetch(\PDO::FETCH_ASSOC);
        return $resultado['ultimo'];
    }
}
?>