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
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function lst_areas(){
			$sql = "SELECT IDAREA, DISTRIBUCION FROM v_areas 
			order by DISTRIBUCION";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function add(){
			$sql="CALL SP_INSERT_CATALOGO (?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idarea, $this->descripcion, $this->alquiler]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function del()
		{
			$sql="CALL SP_DEL_CATALOGO(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcatalogo]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		
		public function edit(){
			$sql="CALL SP_MOD_CATALOGO(?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcatalogo, $this->idarea, $this->descripcion, $this->alquiler]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		
	}

?>