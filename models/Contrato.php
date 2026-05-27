<?php namespace Models;
	
	class Contrato{

		private $idcontrato;
		private $idusuario;
		private $idcliente;
		private $actividad;
		private $razon_social;
		private $contrato;
		private $fecha_inicio;
		private $tiempo_contrato;
		private $_contrato;
		private $iddetalle;
		private $idcatalogo;
		private $idarea;
		
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
			$sql = "SELECT * FROM v_contratos 
			        WHERE VIGENTE='PR'";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function lst_clientes(){
			$sql = "SELECT IDCLIENTE, CONCAT(NOMBRE, ' - ', CEDULA) AS BCLIENTE FROM clientes ORDER BY NOMBRE";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		
		public function lst_catalogo(){
			// Eliminada la columna TIPO que no existe en v_catalogo
			$sql = "SELECT IDCATALOGO, CONCAT(DISTRIBUCION, ' - ', DESCRIPCION ) AS BESPACIO FROM v_catalogo WHERE ESTADO ='DISPONIBLE'";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function lst_areas(){
			$sql = "SELECT IDAREA, DISTRIBUCION FROM v_areas ORDER BY DISTRIBUCION";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function lst_catalogo_por_area(){
			$sql = "SELECT IDCATALOGO, CONCAT(DISTRIBUCION, ' - ', DESCRIPCION ) AS BESPACIO, ALQUILER 
			        FROM v_catalogo WHERE ESTADO ='DISPONIBLE' AND IDAREA = ?";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idarea]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function lst_detalle(){
			// Eliminada la columna TIPO que no existe en v_detalle
			$sql = "SELECT IDDETALLE, IDARRIENDO, DISTRIBUCION, DESCRIPCION, ALQUILER 
			        FROM v_detalle WHERE IDARRIENDO = ?";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcontrato]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function add(){
			$sql="CALL SP_INSERT_CONTRATO (?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([
				$this->idusuario, 
				$this->idcliente, 
				$this->actividad, 
				$this->razon_social,
				$this->contrato,
				$this->fecha_inicio,
				$this->tiempo_contrato
			]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);			
		}

		public function add_detalle(){
			$sql="CALL SP_INSERT_DETALLE (?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcontrato, $this->idcatalogo]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);			
		}

		public function del()
		{
			$sql="CALL SP_DEL_CONTRATO(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcontrato]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function delete_detalle()
		{
			$sql="CALL SP_DEL_DETALLE(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->iddetalle]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function edit(){
			$sql="CALL SP_MOD_CONTRATO (?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([
				$this->idcontrato,
				$this->idcliente,
				$this->actividad,
				$this->razon_social,
				$this->fecha_inicio,
				$this->tiempo_contrato,
				$this->contrato
			]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function confirma_contrato()
		{
			$sql="CALL SP_CONFIRMA_CONTRATO(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcontrato]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		
	}

?>