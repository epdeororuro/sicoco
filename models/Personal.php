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
			$sql = "SELECT * FROM PERSONAS WHERE TIPO_PERSONA LIKE ? order by APELLIDOS ";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->tipo_persona]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function add(){
			$sql="CALL SP_INSERT_PERSONAS(?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([
				$this->nombres,
				$this->apellidos,
				$this->razon_social,
				$this->ci_nit,
				$this->tipo_documento,
				$this->direccion,
				$this->telefonos,
				$this->correo,
				$this->tipo_persona
			]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function del()
		{
			$sql="CALL SP_DEL_PERSONAS(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idpersona]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function edit(){
			$sql = "UPDATE personas SET NOMBRES  = UPPER(?), 
			                         APELLIDOS   = UPPER(?), 
			                         RAZON_SOCIAL= UPPER(?),
			                         CI_NIT      = ?, 
			                         TIPO_DOCUMENTO=UPPER(?),
			                         DIRECCION   = UPPER(?),
			                         TELEFONOS    = ?,
			                         CORREO    = ?,
			                         TIPO_PERSONA       = ?			                         
			        WHERE IDPERSONA = ?";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([
				$this->nombres,
				$this->apellidos,
				$this->razon_social,
				$this->ci_nit,
				$this->tipo_documento,
				$this->direccion,
				$this->telefonos,
				$this->correo,
				$this->tipo_persona,
				$this->idpersona
			]);
		}
		
	}

?>