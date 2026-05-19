<?php namespace Models;
	
	class Rol{

		private $idencargado;
		private $idpersona;
		private $usuario;
		private $clave;
		private $rol;
		private $alta;
		private $baja;
		private $activo;
		
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
			$sql = "SELECT * FROM VISTA_ENCARGADO";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}
		public function lst_operador(){
			$sql = "SELECT concat(IDPERSONA, '-', CI_NIT) AS ID, (CONCAT(NOMBRES,' ', APELLIDOS)) AS NOMBRE 
			        FROM PERSONAS WHERE TIPO_PERSONA LIKE 'OPERADOR'";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function add(){
			//SP_INSERT_ENCARGADO (IDPERSON INTEGER, USR VARCHAR(15), ROLES VARCHAR(15))
			$sql="CALL SP_INSERT_ENCARGADO ('{$this->idpersona}', '{$this->usuario}', 
			                                '{$this->rol}', '{$this->clave}')";
			//$sql="INSERT INTO ENCARGADO (IDPERSONA, USUARIO, CLAVE, ROL)
			 //  VALUES('{$this->idpersona}', '{$this->usuario}', '{$this->clave}', '{$this->rol}')";
			$datos=$this->con->ConsultaRetorno($sql);
			return $datos;			
		}

		public function del()
		{
			$sql="call SP_DEL_ENCARGADO('{$this->idencargado}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function baja(){
			$sql = "UPDATE encargado SET ACTIVO  = 'NO', 
			                         BAJA   = current_timestamp()				                         
			        WHERE IDENCARGADO = '{$this->idencargado}'";
			$retorno= $this->con->consultaSimple($sql);
			return $retorno;
		}
		



/*
		

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
		*/
	}

?>