<?php namespace Models;
	
	class Catalogo{

		private $idcatalogo;
		private $idarea;
		private $tipo;
		private $descripcion;
		private $alquiler;
				
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
			$sql = "SELECT * FROM v_catalogo 
			order by DISTRIBUCION,  ESTADO, DESCRIPCION";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function lst_areas(){
			$sql = "SELECT IDAREA, DISTRIBUCION FROM v_areas 
			order by DISTRIBUCION";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function add(){
			$sql="CALL SP_INSERT_CATALOGO ('{$this->idarea}',
			'{$this->descripcion}', '{$this->alquiler}')";
			$datos=$this->con->ConsultaRetorno($sql);
			return $datos;			
		}

		public function del()
		{
			$sql="call SP_DEL_CATALOGO('{$this->idcatalogo}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}
		
		public function edit(){
			$sql="CALL SP_MOD_CATALOGO
			      ({$this->idcatalogo},'{$this->idarea}',
			      '{$this->descripcion}',
			      '{$this->alquiler}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}
		
	}

?>