<?php
/**
 * 
 */
use Models\Login as Login;
use Config\sessionController as SessionController;
class loginController
{
	private $login;
	private $usuario_sesion;

	public function __construct()
	{
		//echo "<br> controlador incializado";
		$this->login=new Login();
		$this->usuario_sesion=new SessionController();
	}
	
	public function index()
	{
		//$datos=$this->estacion->lst();
		//return $datos;
	}

public function usr()
	{
		if($_POST){
				$this->login->set("nombreusr", $_POST['txt_correo']);
				$password_ingresada = $_POST['txt_clave'];
				
				$datos=$this->login->lst_login();

				if (is_array($datos) && count($datos) > 0)
				{
					$usuario = $datos[0];
					$hash_bd = $usuario['PASS'];
					
					// Validar con Bcrypt o texto plano (Temporalmente habilitado para recuperar acceso)
					if (password_verify($password_ingresada, $hash_bd) || $password_ingresada === $hash_bd) {
						
						// --- AQUÍ INICIA LA MAGIA DEL DOBLE FACTOR (2FA) ---
						$id_usuario = $usuario['IDUSUARIO'];
						$codigo_otp = rand(100000, 999999);
						
						// Guardamos el OTP en la base de datos
						$this->login->set("idusuario", $id_usuario);
						$this->login->set("token", $codigo_otp);
						$this->login->set("ip_acceso", $_SERVER['REMOTE_ADDR']);
						$this->login->insertar_otp();
						
						// --- PREPARACIÓN PARA ENLACE WHATSAPP GRATUITO ---
						$numero = !empty($usuario['CEL']) ? $usuario['CEL'] : "00000000";
						$celular = "591" . $numero;
						
						// Pasamos los datos por sesión temporal para recuperar el nombre al validar
						$_SESSION['datos_usuario_temporal'] = $usuario; 
						
						// Retornamos ID, Celular y el Código para armar el link en Javascript
						echo "2-" . $id_usuario . "-" . $celular . "-" . $codigo_otp;
						exit();
					} else {
						// Contraseña Incorrecta
						echo "0";
						exit();
					}
				} else {
					// Usuario no encontrado o inactivo
					echo "0";
					exit();
				}
		}
	}

	public function validar_otp()
	{
		if($_POST){
			$otp_ingresado = $_POST['txt_otp'];
			$id_usuario = $_POST['id_usuario_tmp'];
			
			$this->login->set("idusuario", $id_usuario);
			$this->login->set("token", $otp_ingresado);
			
			// Validamos directamente contra la Base de Datos
			$es_valido = $this->login->verificar_otp();
			
			if($es_valido) {
				// Inutilizamos el token para evitar ataques de repetición
				$this->login->marcar_otp_usado();
				
				$usuario = $_SESSION['datos_usuario_temporal'];
				
				// 1. Iniciamos las sesiones oficiales
				$this->usuario_sesion->set("nombre", $usuario['USR']);
				$this->usuario_sesion->set("idmiembro", $usuario['IDUSUARIO']);
				$this->usuario_sesion->set("cargo", $usuario['ROL']);
				$this->usuario_sesion->setCurrentUser(); 
				$_SESSION['ultimo_acceso'] = time();
				
				// Limpiamos los temporales
				unset($_SESSION['datos_usuario_temporal']);
				
				echo "1-" . $usuario['USR'];
				exit();
			} else {
				echo "Código de seguridad incorrecto o expirado.";
				exit();
			}
		}
	}

	public function reenviar_otp()
	{
		if (isset($_POST['id_usuario_tmp']) && isset($_SESSION['datos_usuario_temporal'])) {
			$usuario = $_SESSION['datos_usuario_temporal'];
			$id_usuario = $usuario['IDUSUARIO'];
			
			// Generamos un NUEVO código
			$codigo_otp = rand(100000, 999999);
			
			// Guardamos el nuevo OTP en la base de datos
			$this->login->set("idusuario", $id_usuario);
			$this->login->set("token", $codigo_otp);
			$this->login->set("ip_acceso", $_SERVER['REMOTE_ADDR']);
			$this->login->insertar_otp();
			
			$numero = !empty($usuario['CEL']) ? $usuario['CEL'] : "00000000";
			$celular = "591" . $numero;
			
			echo "1-" . $celular . "-" . $codigo_otp;
			exit();
		} else {
			echo "0";
			exit();
		}
	}

	public function logout()
	{
		$this->usuario_sesion->closeSession();
		//echo $this->usuario_sesion->getStatus();
		header('Location:'. URL . "inicio");
	}


}
?>