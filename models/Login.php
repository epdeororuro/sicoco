<?php namespace Models;
	
	class Login{

		private $idusuario;
		private $nombreusr;
		private $rol;
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
			$sql="SELECT u.*, r.SIGLA as ROL
			      FROM usuarios u
			      LEFT JOIN roles r ON u.IDROL = r.IDROL
			      WHERE u.ACTIVO='SI' AND u.USR = '{$this->nombreusr}' LIMIT 1";
			$datos=$this->con->consultaRetorno($sql);
			return $datos;
		}

		// Genera y guarda el código con expiración de 5 minutos
		public function insertar_otp()
		{
			$sql = "INSERT INTO log_accesos (IDUSUARIO, TOKEN, FECHA_EXPIRACION, IP_ACCESO) 
					VALUES ('{$this->idusuario}', '{$this->token}', DATE_ADD(NOW(), INTERVAL 5 MINUTE), '{$this->ip_acceso}')";
			$this->con->consultaSimple($sql);
		}

		// Verifica si el código es válido, pertenece al usuario, no expiró y está pendiente
		public function verificar_otp()
		{
			$sql = "SELECT * FROM log_accesos 
					WHERE IDUSUARIO = '{$this->idusuario}' 
					AND TOKEN = '{$this->token}' 
					AND ESTADO = 'PENDIENTE' 
					AND FECHA_EXPIRACION > NOW() 
					ORDER BY IDLOG DESC LIMIT 1";
			
			$datos = $this->con->consultaRetorno($sql);
			$filas = is_array($datos) ? count($datos) : 0;
			return $filas > 0;
		}

		// Marca el token como usado para que no se pueda reciclar
		public function marcar_otp_usado()
		{
			$sql = "UPDATE log_accesos SET ESTADO = 'USADO' 
					WHERE IDUSUARIO = '{$this->idusuario}' 
					AND TOKEN = '{$this->token}'";
			$this->con->consultaSimple($sql);
		}

	}

?>