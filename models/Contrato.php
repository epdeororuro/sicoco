<?php namespace Models;
	
	class Contrato{

		private $idcontrato;
		private $idusuario;
		private $idcliente;
		private $actividad;
		private $razon_social;
		private $contrato;
		private $fecha_suscripcion;
		private $fecha_inicio;
		private $tiempo_contrato;
		private $_contrato;
		private $iddetalle;
		private $idcatalogo;
		private $idarea;

		// Campos unificados del Cliente
		private $cedula;
		private $nombres;
		private $paterno;
		private $materno;
		private $celular;
		private $direccion;
		
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
			// Se consulta directamente a las tablas para evitar el error de la vista v_contratos (columna NOMBRE)
			// y se incluyen los contratos confirmados ('SI') además de los pendientes ('PR').
			$sql = "SELECT a.IDARRIENDO, a.IDUSUARIO, a.IDCLIENTE, a.ACTIVIDAD, 
			               a.RAZONSOCIAL, a.CONTRATO, a.FECHA_SUSCRIPCION, a.FECHA_INICIO, a.TIEMPOCONTRATO, 
			               a.MONTO, a.OBSERVACIONES, a.VIGENTE, a.FECHA_REGISTRO,
			               CONCAT(c.CEDULA, ' - ', c.NOMBRE_COMPLETO) AS REPRESENTANTE 
			        FROM arriendos a 
			        INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
			        WHERE a.VIGENTE IN ('PR', 'SI') ORDER BY a.IDARRIENDO DESC";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function lst_clientes(){
			$sql = "SELECT IDCLIENTE, CONCAT(NOMBRE_COMPLETO, ' - ', CEDULA) AS BCLIENTE FROM clientes ORDER BY NOMBRE_COMPLETO";
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
			$sql="CALL SP_INSERT_CONTRATO (?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([
				$this->idusuario, 
				$this->idcliente, 
				$this->actividad, 
				$this->razon_social,
				$this->contrato,
				$this->fecha_suscripcion,
				$this->fecha_inicio,
				$this->tiempo_contrato
			]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);			
		}

		public function add_unified(){
			$sql = "CALL SP_NUEVO_ARRENDAMIENTO(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([
				$this->idusuario,
				$this->cedula,
				$this->nombres,
				$this->paterno,
				$this->materno,
				$this->celular,
				$this->direccion,
				$this->idcatalogo,
				$this->actividad,
				$this->razon_social,
				$this->contrato,
				$this->fecha_suscripcion,
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
			$sql="CALL SP_MOD_CONTRATO (?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([
				$this->idcontrato,
				$this->idcliente,
				$this->actividad,
				$this->razon_social,
				$this->fecha_suscripcion,
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