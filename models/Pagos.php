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
			$sql = "UPDATE pagos SET PENDIENTE = 'NO', FECHA_PAGO = NOW(), USR = ? WHERE IDPAGO = ?";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->usr, $this->idpago]);
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