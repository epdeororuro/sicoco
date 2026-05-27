<?php namespace Models;
	
	class Login{

		private $idusuario;
		private $nombreusr;
		private $clave;
		private $token;
		private $ip_acceso;
		
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

		public function lst_login(){
			// Protección contra SQL Injection usando sentencias preparadas con PDO
			$sql = "CALL SP_LOGIN(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->nombreusr]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}


		// Genera y guarda el código con expiración de 5 minutos
		public function insertar_otp()
		{
			$sql = "INSERT INTO log_accesos (IDUSUARIO, TOKEN, FECHA_EXPIRACION, IP_ACCESO) 
					VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE), ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idusuario, $this->token, $this->ip_acceso]);
		}

		// Verifica si el código es válido, pertenece al usuario, no expiró y está pendiente
		public function verificar_otp()
		{
			$sql = "SELECT * FROM log_accesos 
					WHERE IDUSUARIO = ? 
					AND TOKEN = ? 
					AND ESTADO = 'PENDIENTE' 
					AND FECHA_EXPIRACION > NOW() 
					ORDER BY IDLOG DESC LIMIT 1";
			
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idusuario, $this->token]);
			$datos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			$filas = is_array($datos) ? count($datos) : 0;
			return $filas > 0;
		}

		// Marca el token como usado para que no se pueda reciclar
		public function marcar_otp_usado()
		{
			$sql = "UPDATE log_accesos SET ESTADO = 'USADO' 
					WHERE IDUSUARIO = ? AND TOKEN = ?";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idusuario, $this->token]);
		}

	}

?>