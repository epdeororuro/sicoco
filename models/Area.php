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
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function add(){
			$sql="CALL SP_INSERT_AREA (?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->referencia, $this->ubicacion]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function del()
		{
			$sql="CALL SP_DEL_AREA(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idarea]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		
		public function edit(){
			$sql="CALL SP_MOD_AREA(?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idarea, $this->referencia, $this->ubicacion]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		
	}

?>