<?php namespace Models;
	
	class Categoria{

		private $descripcion;
		private $idcategoria;
		private $vigente;
		private $tipo;

		private $operacion;

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
		    $sql = "SELECT * FROM CATEGORIA WHERE VIGENTE LIKE ? AND TIPO LIKE ? ORDER BY VIGENTE ";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->vigente, $this->tipo]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function lst_componente(){
		    $sql = "SELECT * FROM VISTA_DETALLE_ENSAMBLE WHERE IDCATEGORIA = ? AND TIPO NOT LIKE 'VACIO' ORDER BY COMPONENTE";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcategoria]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function add(){
			$sql = "CALL SP_INSERT_CATEGORIA(?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->descripcion, $this->tipo]);
		}

		public function edit()
		{
			$sql="UPDATE CATEGORIA SET DESCRIPCION = UPPER(?) WHERE IDCATEGORIA = ?";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->descripcion, $this->idcategoria]);
		}

		public function del()
		{
			$sql="CALL SP_DEL_CATEGORIA(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcategoria]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function retirar()
		{
			$sql="CALL SP_RETIRA_CATEGORIA(?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcategoria, $this->operacion]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		

	}


?>