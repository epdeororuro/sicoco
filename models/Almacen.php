<?php namespace Models;
	
	class Almacen{

		private $descripcion;
		private $ubicacion;
		private $contactos;
		private $tipo;
		private $activo;
		private $idalmacen;

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
		    $sql = "SELECT * FROM almacen WHERE TIPO LIKE 'ALMACEN' OR TIPO LIKE 'TIENDA' ORDER BY ACTIVO, TIPO";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function add(){
			$sql = "INSERT INTO almacen (DESCRIPCION, UBICACION, CONTACTOS, TIPO) VALUES (UPPER(?), UPPER(?), ?, UPPER(?))";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->descripcion, $this->ubicacion, $this->contactos, $this->tipo]);
		}

		public function edit()
		{
			$sql="UPDATE ALMACEN SET DESCRIPCION = UPPER(?), 
			                         UBICACION = UPPER(?), 
			                         CONTACTOS = ?, 
			                         TIPO = UPPER(?)
			      WHERE IDALMACEN = ?";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->descripcion, $this->ubicacion, $this->contactos, $this->tipo, $this->idalmacen]);
		}

		public function del()
		{
			$sql="CALL SP_DEL_ALMACEN(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idalmacen]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

	}

//
//$a=new Almacen();
//echo json_encode($a->lst());
?>