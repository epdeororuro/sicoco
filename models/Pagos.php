<?php namespace Models;
	
	class Pagos{

		private $idpago;
		private $idarriendo;
		private $usr;
		private $idcliente;
		private $nombre;
		private $cedula;
		private $contactos;
		private $direccion;
		private $nro_recibo;
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
			/*$sql = "SELECT IDARRIENDO, 
			CONCAT(REPRESENTANTE, ' - ', ACTIVIDAD, ' - Contrato:', 
                   CONTRATO, ' - Fecha de Inicio:', FECHA_INICIO,
                   ' - Mensualidad:', MONTO, 'Bs. - Tiempo:',
                   TIEMPOCONTRATO, ' Meses') AS DESCRIPCION 
			FROM v_contratos where VIGENTE='SI' 
			order by REPRESENTANTE";
*/
			$sql= "SELECT * FROM V_RESUMEN_GRAL_CONTRATO 
			WHERE VIGENTE ='SI'";


			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function lst_pagos(){
			
			$sql= "SELECT * FROM V_RESUMEN_PAGOS 
			WHERE PENDIENTE ='SI' 
			AND IDARRIENDO='{$this->idarriendo}' ";


			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function lst_detalle(){
			
			$sql= "SELECT * FROM V_DETALLE
			WHERE IDARRIENDO='{$this->idarriendo}' ";

			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function obtener_datos_recibo(){
			$sql = "SELECT p.IDPAGO, p.PERIODO, p.MONTO, p.FECHA_PAGO, p.USR,
			               a.CONTRATO, a.ACTIVIDAD,
			               c.NOMBRE_COMPLETO AS CLIENTE, c.CEDULA
			        FROM pagos p
			        INNER JOIN arriendos a ON p.IDARRIENDO = a.IDARRIENDO
			        INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
			        WHERE p.IDPAGO = ? LIMIT 1";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idpago]);
			return $stmt->fetch(\PDO::FETCH_ASSOC);
		}

		public function obtener_datos_recibos_multiples($ids){
			$inQuery = implode(',', array_fill(0, count($ids), '?'));
			$sql = "SELECT p.IDPAGO, p.PERIODO, p.MONTO, p.FECHA_PAGO, p.USR,
			               a.CONTRATO, a.ACTIVIDAD,
			               c.NOMBRE_COMPLETO AS CLIENTE, c.CEDULA
			        FROM pagos p
			        INNER JOIN arriendos a ON p.IDARRIENDO = a.IDARRIENDO
			        INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
			        WHERE p.IDPAGO IN ($inQuery) 
			        ORDER BY p.IDPAGO ASC";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute($ids);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function plan_pagos(){
			$sql= "SELECT IDPAGO, PERIODO, MONTO, PENDIENTE 
			       FROM pagos 
			       WHERE IDARRIENDO = ?
			       ORDER BY PERIODO ASC";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idarriendo]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function registrar_pago(){
			$sql = "UPDATE pagos SET PENDIENTE = 'NO', FECHA_PAGO = NOW(), USR = ?, NRO_RECIBO = ? WHERE IDPAGO = ?";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->usr, $this->nro_recibo, $this->idpago]);
			return true;
		}

		public function verificar_y_liberar_contrato_por_pago($idpago) {
			// 1. Encontrar a qué contrato pertenece el pago recién hecho
			$sql = "SELECT IDARRIENDO FROM pagos WHERE IDPAGO = ?";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$idpago]);
			$res = $stmt->fetch(\PDO::FETCH_ASSOC);
			
			if ($res) {
				$idarriendo = $res['IDARRIENDO'];
				
				// 2. Contar si todavía quedan pagos pendientes en ese contrato
				$sql2 = "SELECT COUNT(*) as pendientes FROM pagos WHERE IDARRIENDO = ? AND PENDIENTE = 'SI'";
				$stmt2 = $this->con->conexion->prepare($sql2);
				$stmt2->execute([$idarriendo]);
				$res2 = $stmt2->fetch(\PDO::FETCH_ASSOC);
				
				// 3. Si no debe nada y su estado era Cuentas por Cobrar (CXC), lo liberamos pasándolo a Finalizado (FIN)
				if ($res2['pendientes'] == 0) {
					$sql3 = "UPDATE arriendos SET VIGENTE = 'FIN' WHERE IDARRIENDO = ? AND VIGENTE = 'CXC'";
					$stmt3 = $this->con->conexion->prepare($sql3);
					$stmt3->execute([$idarriendo]);
				}
			}
		}

		public function cierre_caja_diario($fecha_inicio, $fecha_fin) {
			$sql = "SELECT IFNULL(p.NRO_RECIBO, LPAD(p.IDPAGO, 6, '0')) AS NRO_RECIBO, 
						   MAX(p.FECHA_PAGO) AS HORA, 
						   SUM(p.MONTO) AS TOTAL,
						   GROUP_CONCAT(p.PERIODO ORDER BY p.PERIODO ASC SEPARATOR ', ') AS PERIODOS, 
						   c.NOMBRE_COMPLETO AS CLIENTE, 
						   a.CONTRATO, 
						   p.USR AS CAJERO
					FROM pagos p
					INNER JOIN arriendos a ON p.IDARRIENDO = a.IDARRIENDO
					INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
					WHERE p.PENDIENTE = 'NO' AND DATE(p.FECHA_PAGO) BETWEEN ? AND ?
					GROUP BY IFNULL(p.NRO_RECIBO, LPAD(p.IDPAGO, 6, '0')), c.NOMBRE_COMPLETO, a.CONTRATO, p.USR
					ORDER BY p.USR ASC, MAX(p.FECHA_PAGO) ASC";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$fecha_inicio, $fecha_fin]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function historial_caja() {
			$sql = "SELECT IFNULL(p.NRO_RECIBO, LPAD(p.IDPAGO, 6, '0')) AS NRO_RECIBO, MAX(p.FECHA_PAGO) AS FECHA, SUM(p.MONTO) AS TOTAL,
						   GROUP_CONCAT(p.PERIODO ORDER BY p.PERIODO ASC SEPARATOR ', ') AS PERIODOS, 
						   c.NOMBRE_COMPLETO AS CLIENTE, a.CONTRATO, p.USR AS CAJERO
					FROM pagos p
					INNER JOIN arriendos a ON p.IDARRIENDO = a.IDARRIENDO
					INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
					WHERE p.PENDIENTE = 'NO'
					GROUP BY IFNULL(p.NRO_RECIBO, LPAD(p.IDPAGO, 6, '0')), c.NOMBRE_COMPLETO, a.CONTRATO, p.USR
					ORDER BY MAX(p.FECHA_PAGO) DESC";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function obtener_ids_por_recibo() {
			$sql = "SELECT IDPAGO FROM pagos WHERE NRO_RECIBO = ? ORDER BY IDPAGO ASC";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->nro_recibo]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function registrar_log_cierre($fecha_inicio, $fecha_fin, $usuario) {
			$sql = "INSERT INTO log_cierres (FECHA_INICIO, FECHA_FIN, USUARIO) VALUES (?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$fecha_inicio, $fecha_fin, $usuario]);
			return true;
		}

		public function add(){
			$sql="CALL SP_INSERT_CLIENTE ('{$this->nombre}',
			'{$this->cedula}', '{$this->contactos}', '{$this->direccion}')";
			$datos=$this->con->consultaRetorno($sql);
			return $datos;			
		}

		public function del()
		{
			$sql="call SP_DEL_CLIENTE('{$this->idcliente}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}		

		public function edit(){
			$sql="CALL SP_MOD_CLIENTE
			      ({$this->idcliente},'{$this->nombre}',
			      '{$this->cedula}','{$this->contactos}',
			      '{$this->direccion}');";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}
		
	}

?>