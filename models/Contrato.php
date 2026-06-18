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
		private $archivo_pdf;

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

		public function obtener_contrato_completo(){
			$sql = "SELECT a.IDARRIENDO, a.ACTIVIDAD, a.RAZONSOCIAL, a.CONTRATO, a.FECHA_SUSCRIPCION, a.FECHA_INICIO, a.TIEMPOCONTRATO, a.ARCHIVO_PDF,
			               c.CEDULA, c.NOMBRES, c.PATERNO, c.MATERNO, c.CONTACTOS AS CELULAR, c.DIRECCION, c.LATITUD, c.LONGITUD,
			               d.IDCATALOGO, cat.IDAREA, d.ALQUILER_NOMINAL AS ALQUILER
			        FROM arriendos a 
			        INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE
			        LEFT JOIN detalle d ON a.IDARRIENDO = d.IDARRIENDO
			        LEFT JOIN catalogo cat ON d.IDCATALOGO = cat.IDCATALOGO
			        WHERE a.IDARRIENDO = ? LIMIT 1";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcontrato]);
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

		public function del()
		{
			$sql="CALL SP_DEL_CONTRATO(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcontrato]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function edit(){
			$sql="CALL SP_MOD_ARRENDAMIENTO_UNIFICADO(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([
				$this->idcontrato,
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

		public function confirma_contrato()
		{
			$sql="CALL SP_CONFIRMA_CONTRATO(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcontrato]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function subir_pdf() {
			$sql = "UPDATE arriendos SET ARCHIVO_PDF = ? WHERE IDARRIENDO = ?";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->archivo_pdf, $this->idcontrato]);
		}

		public function cierre_gestion()
		{
			$sql="CALL SP_CIERRE_GESTION()";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function verificar_mora_cliente($cedula) {
			// Retorna Verdadero si el cliente tiene al menos 1 contrato en estado CXC
			$sql = "SELECT COUNT(*) as moroso 
			        FROM arriendos a 
			        INNER JOIN clientes c ON a.IDCLIENTE = c.IDCLIENTE 
			        WHERE c.CEDULA = ? AND a.VIGENTE = 'CXC'";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$cedula]);
			$res = $stmt->fetch(\PDO::FETCH_ASSOC);
			return ($res['moroso'] > 0);
		}
		
	}

?>