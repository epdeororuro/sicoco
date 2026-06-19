<?php namespace Config;
/**
 * 
 */

class sessionController
{
	private $nombre;
	private $idmiembro;
	private $cargo;
	//private $correo;

	public function __construct()
	//public function iniciar()
	{
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
	}
	}

    public function set($atributo, $contenido){
			$this->$atributo = $contenido;
		}

	public function get($atributo){
			return $this->$atributo;
		}

	public function setCurrentUser()
	{
		$_SESSION['nombre']=$this->nombre;
		$_SESSION['idmiembro']=$this->idmiembro;
		$_SESSION['cargo']=$this->cargo;
		//$_SESSION['correo']=$this->correo;
		
		// Generar token CSRF al iniciar sesión
		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}
	}

	public function validarCSRF($token)
	{
		if (!isset($_SESSION['csrf_token']) || empty($token)) {
			return false;
		}
		return hash_equals($_SESSION['csrf_token'], $token);
	}

	public function registrarActividad($accion, $descripcion)
	{
		try {
			$idusuario = isset($_SESSION['idmiembro']) ? $_SESSION['idmiembro'] : null;
			$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
			$ua = isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 255) : 'Unknown';

			// Crear la conexión y registrar la actividad
			$db = new \Models\Conexion();
			$sql = "INSERT INTO log_actividades (IDUSUARIO, ACCION, DESCRIPCION, IP_ORIGEN, USER_AGENT) VALUES (?, ?, ?, ?, ?)";
			$stmt = $db->conexion->prepare($sql);
			$stmt->execute([$idusuario, $accion, $descripcion, $ip, $ua]);
			return true;
		} catch (\Exception $e) {
			error_log("Error al registrar actividad en bitacora: " . $e->getMessage());
			return false;
		}
	}

	public function verifica()
	{
		if(isset($_SESSION['nombre'] ) && isset($_SESSION['idmiembro'] ) && isset($_SESSION['cargo'] ))
			return 1;
		else
			return 0;
	}

	public function getCurrentUser()
	{
		return $_SESSION;
	}

	public function closeSession()
	{
		session_unset();
		session_destroy();
	}

	public function getStatus()
	{
		return session_status();
	}

	public function index()
	{
		//	echo "<br> Sistema integrado para control de calidad y Asistencia Tecnica";
		//	header('Location:'. URL . "login");
	}

	
}
?>