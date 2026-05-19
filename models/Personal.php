<?php namespace Models;
	
	class Personal{

		private $idpersona;
		private $nombres;
		private $apellidos;
		private $razon_social;
		private $ci_nit;
		private $tipo_documento;
		private $direccion;
		private $telefonos;
		private $correo;
		private $tipo_persona;
		private $foto;
		

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
			$sql = "SELECT * FROM PERSONAS WHERE TIPO_PERSONA LIKE '{$this->tipo_persona}' order by APELLIDOS ";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function add(){
			$sql="CALL SP_INSERT_PERSONAS('{$this->nombres}', '{$this->apellidos}', '{$this->razon_social}', 
			              '{$this->ci_nit}', '{$this->tipo_documento}', '{$this->direccion}','{$this->telefonos}' , 
			              '{$this->correo}', '{$this->tipo_persona}')";
			$datos=$this->con->consultaRetorno($sql);
			return $datos;			
		}

		public function del()
		{
			$sql="call SP_DEL_PERSONAS('{$this->idpersona}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function edit(){
			$sql = "UPDATE personas SET NOMBRES  = UPPER('{$this->nombres}'), 
			                         APELLIDOS   = UPPER('{$this->apellidos}'), 
			                         RAZON_SOCIAL= UPPER('{$this->razon_social}'),
			                         CI_NIT      = '{$this->ci_nit}', 
			                         TIPO_DOCUMENTO=UPPER('{$this->tipo_documento}'),
			                         DIRECCION   = UPPER('{$this->direccion}'),
			                         TELEFONOS    = '{$this->telefonos}',
			                         CORREO    = '{$this->correo}',
			                         TIPO_PERSONA       = '{$this->tipo_persona}'			                         
			        WHERE IDPERSONA = '{$this->idpersona}'";
			$retorno= $this->con->consultaSimple($sql);
			return $retorno;
		}
		
	}

?>