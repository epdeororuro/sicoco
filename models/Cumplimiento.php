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
    public $motivo_anulacion;
    public $usuario_anulacion;

    public function __construct(){ 
        $this->con = new Conexion(); 
    }

    public function lst(){
        $sql = "SELECT g.*, c.IDAREA, c.DESCRIPCION as ITEM, a.REFERENCIA, a.UBICACION, 
                IFNULL(arr.CONTRATO, 'SIN CONTRATO') AS NRO_CONTRATO
                FROM garantias_cumplimiento g 
                INNER JOIN catalogo c ON g.IDCATALOGO = c.IDCATALOGO 
                INNER JOIN areaubicacion a ON c.IDAREA = a.IDAREA 
                LEFT JOIN arriendos arr ON g.IDARRIENDO = arr.IDARRIENDO
                ORDER BY g.IDGARANTIA DESC";
        return $this->con->conexion->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function add(){
        $sql = "INSERT INTO garantias_cumplimiento (CITE_ADJUDICACION, CI_POSTULANTE, NOMBRE_POSTULANTE, IDCATALOGO, MONTO, USUARIO) VALUES (UPPER(?), ?, UPPER(?), ?, ?, ?)";
        $stmt = $this->con->conexion->prepare($sql);
        if ($stmt->execute([$this->cite, $this->ci, $this->nombre, $this->idcatalogo, $this->monto, $this->usuario])) {
            return $this->con->conexion->lastInsertId();
        }
        return false;
    }

    public function edit(){
        $sql = "UPDATE garantias_cumplimiento SET CITE_ADJUDICACION = UPPER(?), CI_POSTULANTE = ?, NOMBRE_POSTULANTE = UPPER(?), IDCATALOGO = ?, MONTO = ? WHERE IDGARANTIA = ?";
        $stmt = $this->con->conexion->prepare($sql);
        return $stmt->execute([$this->cite, $this->ci, $this->nombre, $this->idcatalogo, $this->monto, $this->idgarantia]);
    }

    public function devolver(){
        $sql = "UPDATE garantias_cumplimiento SET ESTADO = 'DEVUELTA', FECHA_DEVOLUCION = CURRENT_TIMESTAMP WHERE IDGARANTIA = ?";
        $stmt = $this->con->conexion->prepare($sql);
        return $stmt->execute([$this->idgarantia]);
    }

    public function anular(){
        $sql = "UPDATE garantias_cumplimiento SET ESTADO = 'ANULADA', MOTIVO_ANULACION = ?, USUARIO_ANULACION = ?, FECHA_ANULACION = CURRENT_TIMESTAMP WHERE IDGARANTIA = ? AND ESTADO = 'RETENIDA'";
        $stmt = $this->con->conexion->prepare($sql);
        return $stmt->execute([$this->motivo_anulacion, $this->usuario_anulacion, $this->idgarantia]);
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
        $sql = "SELECT g.*, c.DESCRIPCION as ITEM, a.REFERENCIA, a.UBICACION, g.USUARIO_ANULACION, g.MOTIVO_ANULACION
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

    public function verificar_mora_cliente($cedula) {
        $sql = "SELECT COUNT(*) as moroso 
                FROM arriendos a 
                INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE 
                WHERE c.CEDULA = ? AND a.VIGENTE = 'CXC'";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$cedula]);
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        return ($res['moroso'] > 0);
    }
}
?>