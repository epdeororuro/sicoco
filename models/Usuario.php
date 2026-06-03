<?php namespace Models;
	
	class Usuario{

		private $idusuario;
		private $nombre;
		private $usuario;
		private $clave;
		private $fecha_alta;
		private $fecha_baja;
		private $idrol;
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
			$sql = "SELECT u.*, r.DESCRIPCION as ROL_DESCRIPCION FROM usuarios u
			LEFT JOIN roles r ON u.IDROL = r.IDROL
			ORDER BY u.ACTIVO, u.IDROL, u.NOMBRE";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function lst_roles(){
			$sql = "SELECT IDROL, DESCRIPCION FROM roles ORDER BY DESCRIPCION";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function add(){
			$sql="CALL SP_INSERT_USUARIO ('{$this->nombre}', '{$this->usuario}', '{$this->clave}', '{$this->idrol}')";
			$datos=$this->con->consultaRetorno($sql);
			return $datos;			
		}

		public function del()
		{
			$sql="call SP_DEL_USUARIO('{$this->idusuario}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function baja()
		{
			$sql="call SP_BAJA_USUARIO('{$this->idusuario}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function reactivar()
		{
			$sql = "UPDATE usuarios SET ACTIVO='SI', FECHA_BAJA=NULL WHERE IDUSUARIO='{$this->idusuario}'";
			$retorno = $this->con->ConsultaSimple($sql);
			if ($retorno == 1) {
				return array(array('OP' => '1'));
			} else {
				return array(array('OP' => 'Error al reactivar el usuario'));
			}
		}

		public function edit(){
			$sql="CALL SP_MODIFICAR_USUARIO
			      ({$this->idusuario},'{$this->nombre}', '{$this->usuario}','{$this->clave}', '{$this->idrol}');";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function cambiar_clave()
		{
			$sql = "UPDATE usuarios SET PASS = ? WHERE IDUSUARIO = ?";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->clave, $this->idusuario]);
		}
		
	}

?>