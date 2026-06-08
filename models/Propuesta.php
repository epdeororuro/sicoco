<?php namespace Models;
	
class Propuesta {

    private $idpropuesta;
    private $ci_postulante;
    private $nombre_postulante;
    private $idcatalogo;
    private $usuario;
    private $con;

    public function __construct(){
        $this->con = new Conexion();
    }

    public function set($atributo, $contenido){
        $this->$atributo = $contenido;
    }

    public function get($atributo){
        return $this->$atributo;
    }

    public function lst(){
        $sql = "SELECT p.*, a.IDAREA, CONCAT(a.REFERENCIA, ' - ', a.UBICACION, ' / ', c.DESCRIPCION) AS ESPACIO 
                FROM propuestas p 
                INNER JOIN catalogo c ON p.IDCATALOGO = c.IDCATALOGO 
                INNER JOIN areaubicacion a ON c.IDAREA = a.IDAREA
                ORDER BY p.IDPROPUESTA DESC";
        $stmt = $this->con->conexion->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function add(){
        // La tabla propuestas no tiene AUTO_INCREMENT en el diseño original, generamos el ID manualmente
        $sql_id = "SELECT COALESCE(MAX(IDPROPUESTA), 0) + 1 FROM propuestas";
        $stmt_id = $this->con->conexion->query($sql_id);
        $next_id = $stmt_id->fetchColumn();

        $sql = "INSERT INTO propuestas (IDPROPUESTA, CI_POSTULANTE, NOMBRE_POSTULANTE, IDCATALOGO, USUARIO) 
                VALUES (?, ?, UPPER(?), ?, ?)";
        $stmt = $this->con->conexion->prepare($sql);
        if ($stmt->execute([$next_id, $this->ci_postulante, $this->nombre_postulante, $this->idcatalogo, $this->usuario])) {
            return $next_id; // Retorna el ID generado para imprimir el recibo
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

}
?>