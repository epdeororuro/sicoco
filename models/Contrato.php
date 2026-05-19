<?php namespace Models;
	
	class Contrato{

		private $idcontrato;
		private $idusuario;
		private $idcliente;
		private $actividad;
		private $razon_social;
		private $contrato;
		private $fecha_inicio;
		private $tiempo_contrato;
		private $_contrato;
		private $iddetalle;
		
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
			//$sql = "SELECT * FROM v_contratos 			        WHERE VIGENTE='PR' AND IDUSUARIO='{$this->idusuario}'";
			$sql = "SELECT * FROM v_contratos 
			        WHERE VIGENTE='PR'";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function lst_clientes(){
			$sql = "SELECT IDCLIENTE, CONCAT(NOMBRE, ' - ', CEDULA) AS BCLIENTE FROM clientes ORDER BY NOMBRE";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		
		public function lst_catalogo(){
			$sql = "SELECT IDCATALOGO, CONCAT(DISTRIBUCION, ' - ', TIPO, ' - ', DESCRIPCION ) AS BESPACIO FROM v_catalogo WHERE ESTADO ='DISPONIBLE'";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function lst_detalle(){
			$sql = "SELECT IDDETALLE, IDARRIENDO, 
			        DISTRIBUCION, TIPO, DESCRIPCION,ALQUILER 
			        FROM v_detalle 
			        WHERE IDARRIENDO='{$this->idcontrato}'";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function add(){
			$sql="CALL SP_INSERT_CONTRATO ('{$this->idusuario}',
			'{$this->idcliente}', '{$this->actividad}', 
			'{$this->razon_social}','{$this->contrato}',
			'{$this->fecha_inicio}','{$this->tiempo_contrato}')";
			$datos=$this->con->consultaRetorno($sql);
			return $datos;			
		}

		public function add_detalle(){
			$sql="CALL SP_INSERT_DETALLE ('{$this->idcontrato}',
			'{$this->idcatalogo}')";
			$datos=$this->con->consultaRetorno($sql);
			return $datos;			
		}

		public function del()
		{
			$sql="call SP_DEL_CONTRATO('{$this->idcontrato}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function delete_detalle()
		{
			$sql="call SP_DEL_DETALLE('{$this->iddetalle}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function edit(){
			$sql="CALL SP_MOD_CONTRATO
			 ('{$this->idcontrato}','{$this->idcliente}', 
			 '{$this->actividad}','{$this->razon_social}',
			 '{$this->fecha_inicio}','{$this->tiempo_contrato}',
			 '{$this->contrato}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function confirma_contrato()
		{
			$sql="call SP_CONFIRMA_CONTRATO	('{$this->idcontrato}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}
		
	}

?>