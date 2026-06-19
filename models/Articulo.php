<?php namespace Models;
	
	class Articulo{

		private $idarticulo;
		private $idcategoria;
		private $descripcion;
		private $minimo;
		private $codbarra;
		private $tipo;
		
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
			$sql = "SELECT * FROM VISTA_ARTICULO_CATEGORIA WHERE TIPO LIKE ? 
			        ORDER BY C_DESCRIPCION, DESCRIPCION";			        
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->tipo]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		public function lst_categoria(){
			$sql = "SELECT IDAREA, concat(REFERENCIA, '->', UBICACION ) as DISTRIBUCION FROM areaubicacion 
			order by DISTRIBUCION";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function add()
		{			
			$sql="INSERT INTO ARTICULO(IDCATEGORIA, DESCRIPCION, MINIMO, CODBARRA, TIPO) VALUES (?, UPPER(?), ?, ?, UPPER(?))";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->idcategoria, $this->descripcion, $this->minimo, $this->codbarra, $this->tipo]);
		}

		public function edit()
		{
			$sql="UPDATE ARTICULO SET DESCRIPCION = UPPER(?), MINIMO = ?, CODBARRA = ? WHERE IDARTICULO = ?";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->descripcion, $this->minimo, $this->codbarra, $this->idarticulo]);
		}

		public function cambio()
		{
			$sql="UPDATE ARTICULO SET IDCATEGORIA = ? WHERE IDARTICULO = ?";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->idcategoria, $this->idarticulo]);
		}

		public function del()
		{
			$sql="CALL SP_DEL_ARTICULO(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idarticulo]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

	}

?>