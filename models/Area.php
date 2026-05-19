<?php namespace Models;
	
	class Area{

		private $idarea;
		private $referencia;
		private $ubicacion;
		
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
			$sql = "SELECT * FROM areaubicacion 
			order by REFERENCIA, UBICACION";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function add(){
			$sql="CALL SP_INSERT_AREA ('{$this->referencia}',
			'{$this->ubicacion}')";
			$datos=$this->con->consultaRetorno($sql);
			return $datos;			
		}

		public function del()
		{
			$sql="call SP_DEL_AREA('{$this->idarea}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}
		
		public function edit(){
			$sql="CALL SP_MOD_AREA
			      ({$this->idarea},'{$this->referencia}',
			      '{$this->ubicacion}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}
		
	}

?>