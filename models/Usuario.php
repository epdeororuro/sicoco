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
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function lst_roles(){
			$sql = "SELECT IDROL, DESCRIPCION FROM roles ORDER BY DESCRIPCION";
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function add(){
			try {
				$sql = "SELECT COUNT(*) FROM usuarios WHERE USR = ?";
				$stmt = $this->con->conexion->prepare($sql);
				$stmt->execute([$this->usuario]);
				if ($stmt->fetchColumn() > 0) {
					return 'Error, el Nombre de Usuario debe ser diferente';
				}
				
				$sql_ins = "INSERT INTO usuarios (NOMBRE, USR, PASS, IDROL) VALUES (UPPER(?), ?, ?, ?)";
				$stmt_ins = $this->con->conexion->prepare($sql_ins);
				$stmt_ins->execute([$this->nombre, $this->usuario, $this->clave, $this->idrol]);
				
				return '1';
			} catch (\PDOException $e) {
				return 'Error al registrar el usuario en la BD';
			}
		}

		public function del()
		{
			try {
				$sql="DELETE FROM usuarios WHERE IDUSUARIO = ?";
				$stmt = $this->con->conexion->prepare($sql);
				$stmt->execute([$this->idusuario]);
				return '1';
			} catch (\PDOException $e) {
				return 'Error, El registro tiene historial de dependencias.';
			}
		}

		public function baja()
		{
			try {
				$sql="UPDATE usuarios SET ACTIVO='NO', FECHA_BAJA=current_timestamp() WHERE IDUSUARIO = ?";
				$stmt = $this->con->conexion->prepare($sql);
				$stmt->execute([$this->idusuario]);
				return '1';
			} catch (\PDOException $e) {
				return 'Error al dar de baja el usuario';
			}
		}

		public function reactivar()
		{
			try {
				$sql = "UPDATE usuarios SET ACTIVO='SI', FECHA_BAJA=NULL WHERE IDUSUARIO=?";
				$stmt = $this->con->conexion->prepare($sql);
				$stmt->execute([$this->idusuario]);
				return '1';
			} catch (\PDOException $e) {
				return 'Error al reactivar el usuario';
			}
		}

		public function edit(){
			try {
				$sql = "SELECT COUNT(*) FROM usuarios WHERE USR = ? AND IDUSUARIO != ?";
				$stmt = $this->con->conexion->prepare($sql);
				$stmt->execute([$this->usuario, $this->idusuario]);
				if ($stmt->fetchColumn() > 0) {
					return 'Error, el Nombre de Usuario debe ser diferente';
				}

				if (!empty($this->clave)) {
					$sql_upd = "UPDATE usuarios SET NOMBRE = UPPER(?), USR = ?, PASS = ?, IDROL = ? WHERE IDUSUARIO = ?";
					$stmt_upd = $this->con->conexion->prepare($sql_upd);
					$stmt_upd->execute([$this->nombre, $this->usuario, $this->clave, $this->idrol, $this->idusuario]);
				} else {
					$sql_upd = "UPDATE usuarios SET NOMBRE = UPPER(?), USR = ?, IDROL = ? WHERE IDUSUARIO = ?";
					$stmt_upd = $this->con->conexion->prepare($sql_upd);
					$stmt_upd->execute([$this->nombre, $this->usuario, $this->idrol, $this->idusuario]);
				}
				
					return '1';
			} catch (\PDOException $e) {
				return 'Error al actualizar el usuario en la BD';
			}
		}

		public function cambiar_clave()
		{
			$sql = "UPDATE usuarios SET PASS = ? WHERE IDUSUARIO = ?";
			$stmt = $this->con->conexion->prepare($sql);
			return $stmt->execute([$this->clave, $this->idusuario]);
		}
		
	}

?>