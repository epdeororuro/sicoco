<?php namespace Models;

class Estorno {
    private $con;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function buscar_recibo($nro_recibo) {
        // 1. Buscar en recibos válidos
        $sql = "SELECT p.NRO_RECIBO, p.FECHA_PAGO, SUM(p.MONTO) AS TOTAL, 'ACTIVO' AS ESTADO_RECIBO, '' AS MOTIVO_ANULACION, p.USR,
                       c.NOMBRE_COMPLETO AS CLIENTE, GROUP_CONCAT(p.PERIODO ORDER BY p.PERIODO ASC SEPARATOR ', ') AS PERIODOS 
                FROM pagos p 
                INNER JOIN arriendos a ON p.IDARRIENDO = a.IDARRIENDO
                INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
                WHERE p.NRO_RECIBO = ? AND p.PENDIENTE = 'NO'
                GROUP BY p.NRO_RECIBO, p.FECHA_PAGO, p.USR, c.NOMBRE_COMPLETO";
        $stmt = $this->con->conexion->prepare($sql);
        $stmt->execute([$nro_recibo]);
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($res) return $res;

        // 2. Buscar en la bóveda de anulados
        $sql2 = "SELECT NRO_RECIBO, FECHA_COBRO AS FECHA_PAGO, MONTO_TOTAL AS TOTAL, 'ANULADO' AS ESTADO_RECIBO, MOTIVO AS MOTIVO_ANULACION, CAJERO_ORIGINAL AS USR, CLIENTE, PERIODOS_COBRADOS AS PERIODOS 
                 FROM log_estornos WHERE NRO_RECIBO = ?";
        $stmt2 = $this->con->conexion->prepare($sql2);
        $stmt2->execute([$nro_recibo]);
        return $stmt2->fetch(\PDO::FETCH_ASSOC);
    }

    public function anular_recibo($nro_recibo, $motivo, $usuario_que_anula) {
        // 1. Fotografía de Auditoría: Recopilar TODOS los datos antes de borrar
        $sql_datos = "SELECT p.IDARRIENDO, a.CONTRATO, a.ACTIVIDAD, c.NOMBRE_COMPLETO AS CLIENTE, c.CEDULA, p.USR, p.FECHA_PAGO, SUM(p.MONTO) AS TOTAL, GROUP_CONCAT(p.PERIODO ORDER BY p.PERIODO ASC SEPARATOR ', ') AS PERIODOS, p.METODO_PAGO, p.NRO_COMPROBANTE, p.NRO_FACTURA_SIAT 
                      FROM pagos p 
                      INNER JOIN arriendos a ON p.IDARRIENDO = a.IDARRIENDO
                      INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
                      WHERE p.NRO_RECIBO = ? AND p.PENDIENTE = 'NO'
                      GROUP BY p.IDARRIENDO, a.CONTRATO, a.ACTIVIDAD, c.NOMBRE_COMPLETO, c.CEDULA, p.USR, p.FECHA_PAGO, p.METODO_PAGO, p.NRO_COMPROBANTE, p.NRO_FACTURA_SIAT";
        $stmt_datos = $this->con->conexion->prepare($sql_datos);
        $stmt_datos->execute([$nro_recibo]);
        $datos = $stmt_datos->fetch(\PDO::FETCH_ASSOC);

        if (!$datos) return false; // El recibo no existe o ya estaba anulado

        $idarriendo = $datos['IDARRIENDO'];

        // 2. Bóveda: Insertar fotografía en log_estornos
        $sql_log = "INSERT INTO log_estornos (NRO_RECIBO, MONTO_TOTAL, PERIODOS_COBRADOS, CLIENTE, CEDULA, CONTRATO, ACTIVIDAD, METODO_PAGO, NRO_COMPROBANTE, NRO_FACTURA_SIAT, CAJERO_ORIGINAL, FECHA_COBRO, USUARIO_QUE_ANULA, MOTIVO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_log = $this->con->conexion->prepare($sql_log);
        $stmt_log->execute([$nro_recibo, $datos['TOTAL'], $datos['PERIODOS'], $datos['CLIENTE'], $datos['CEDULA'], $datos['CONTRATO'], $datos['ACTIVIDAD'], $datos['METODO_PAGO'], $datos['NRO_COMPROBANTE'], $datos['NRO_FACTURA_SIAT'], $datos['USR'], $datos['FECHA_PAGO'], $usuario_que_anula, $motivo]);

        // 3. Rollback: Limpiar el Plan de Pagos para que vuelva a estar virgen
        $sql_upd = "UPDATE pagos SET PENDIENTE = 'SI', NRO_RECIBO = NULL, FECHA_PAGO = NULL, USR = NULL, METODO_PAGO = NULL, NRO_COMPROBANTE = NULL, NRO_FACTURA_SIAT = NULL, ESTADO_RECIBO = 'ACTIVO', MOTIVO_ANULACION = NULL WHERE NRO_RECIBO = ?";
        $stmt_upd = $this->con->conexion->prepare($sql_upd);
        $stmt_upd->execute([$nro_recibo]);

        // 4. Restauración de Contrato: Si al anular vuelve a haber deuda, restauramos su vigencia
        $sql_res = "UPDATE arriendos SET VIGENTE = 'CXC' WHERE VIGENTE = 'FIN' AND IDARRIENDO = ?";
        $stmt_res = $this->con->conexion->prepare($sql_res);
        $stmt_res->execute([$idarriendo]);

        return true;
    }
}
?>