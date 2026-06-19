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
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		public function lst_operador(){
			$sql = "SELECT concat(IDPERSONA, '-', CI_NIT) AS ID, (CONCAT(NOMBRES,' ', APELLIDOS)) AS NOMBRE 
			        FROM PERSONAS WHERE TIPO_PERSONA LIKE 'OPERADOR'";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function add(){
			$sql="CALL SP_INSERT_ENCARGADO (?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idpersona, $this->usuario, $this->rol, $this->clave]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function del()
		{
			$sql="CALL SP_DEL_ENCARGADO(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idencargado]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function baja(){
			$sql = "UPDATE encargado SET ACTIVO  = 'NO', 
			                         BAJA   = current_timestamp()				                         
			        WHERE IDENCARGADO = ?";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->idencargado]);
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