<?php
use Models\Usuario as Usuario;
use Config\sessionController as SessionController;
class usuarioController
{
	private $usuario;
	private $usuario_session;
	public function __construct()
	{
		$this->usuario_session=new SessionController();
		if ($this->usuario_session->verifica()) {
			$currentUser = $this->usuario_session->getCurrentUser();
			if (!isset($currentUser['cargo']) || $currentUser['cargo'] != 1) {
				// Si no es Administrador, solo permitimos cambiar_clave
				$url = isset($_GET['url']) ? filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL) : '';
				$partes = explode('/', $url);
				$metodo = isset($partes[1]) ? strtolower($partes[1]) : 'index';
				if ($metodo !== 'cambiar_clave') {
					header('Location:'. URL . "inicio");
					exit();
				}
			}
			$this->usuario=new Usuario();
		}
		else
		{
			header('Location:'. URL . "login");
			exit();
		}
	}
	
	public function index()
	{
		//$datos=$this->usuario->lst();
		//return $datos;
	}

	public function listar()
	{
	  $datos=$this->usuario->lst();
	  echo json_encode($datos);
	  exit();

	}

	public function listar_roles()
	{
	  $datos=$this->usuario->lst_roles();
	  echo json_encode($datos);
	  exit();
	}

public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_nombre'];
			if (empty($_POST['txt_nombre'])||
				empty($_POST['txt_usuario'])||
				empty($_POST['txt_clave'])||
				empty($_POST['txt_idrol']))				
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
				$this->usuario->set("nombre", $_POST['txt_nombre']);
				$this->usuario->set("usuario", $_POST['txt_usuario']);
				$this->usuario->set("clave", password_hash($_POST['txt_clave'], PASSWORD_BCRYPT));
				$this->usuario->set("idrol", $_POST['txt_idrol']);
								
				$datos=$this->usuario->add();	
	        	if (ob_get_length()) ob_clean();
				$respuesta = is_array($datos) ? (isset($datos[0]['OP']) ? $datos[0]['OP'] : '1') : $datos;
				if ($respuesta == '1') {
					$this->usuario_session->registrarActividad('USUARIO_CREADO', 'Creó el usuario operador: ' . $_POST['txt_usuario']);
				}
	        	echo trim((string)$respuesta);
	        	exit();
			}
		}
		else
			{
				$respuesta="Error al enviar los Datos";
			}
   	      if (ob_get_length()) ob_clean();
   	      echo trim((string)$respuesta);
   	      exit();
	}

public function edit()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_descripcion'];
			if (empty($_POST['txt_idusuario'])||
				empty($_POST['txt_nombre'])||
				empty($_POST['txt_usuario'])||
				empty($_POST['txt_idrol']))
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
				$this->usuario->set("idusuario", $_POST['txt_idusuario']);
				$this->usuario->set("nombre", $_POST['txt_nombre']);
				$this->usuario->set("usuario", $_POST['txt_usuario']);
				if (!empty($_POST['txt_clave'])) {
				    $this->usuario->set("clave", password_hash($_POST['txt_clave'], PASSWORD_BCRYPT));
				} else {
				    $this->usuario->set("clave", "");
				}
				$this->usuario->set("idrol", $_POST['txt_idrol']);
				
	        	$datos=$this->usuario->edit();	
	        	if (ob_get_length()) ob_clean();
				$respuesta = is_array($datos) ? (isset($datos[0]['OP']) ? $datos[0]['OP'] : '1') : $datos;
				if ($respuesta == '1') {
					$this->usuario_session->registrarActividad('USUARIO_MODIFICADO', 'Modificó el usuario ID: ' . $_POST['txt_idusuario'] . ' (Login: ' . $_POST['txt_usuario'] . ')');
				}
	        	echo trim((string)$respuesta);
	        	exit();
			}
		}
		else
			{
				$respuesta="Error al enviar los Datos";
			}
   	      if (ob_get_length()) ob_clean();
   	      echo trim((string)$respuesta);
   	      exit();
	}

public function delete($argumento)
{  $respuesta="valor inicial";
	$this->usuario->set("idusuario", $argumento);	
	$datos=$this->usuario->del();
	if (ob_get_length()) ob_clean();
	$respuesta = is_array($datos) ? (isset($datos[0]['OP']) ? $datos[0]['OP'] : '1') : $datos;
	if ($respuesta == '1') {
		$this->usuario_session->registrarActividad('USUARIO_ELIMINADO', 'Eliminó permanentemente el usuario ID: ' . $argumento);
	}
	echo trim((string)$respuesta);
	exit();	
}

public function baja($argumento)
{  $respuesta="valor inicial";
	$this->usuario->set("idusuario", $argumento);	
	$datos=$this->usuario->baja();
	if (ob_get_length()) ob_clean();
	$respuesta = is_array($datos) ? (isset($datos[0]['OP']) ? $datos[0]['OP'] : '1') : $datos;
	if ($respuesta == '1') {
		$this->usuario_session->registrarActividad('USUARIO_DESACTIVADO', 'Dio de baja al usuario ID: ' . $argumento);
	}
	echo trim((string)$respuesta);
	exit();	
}

public function reactivar($argumento)
{  $respuesta="valor inicial";
	$this->usuario->set("idusuario", $argumento);	
	$datos=$this->usuario->reactivar();
	if (ob_get_length()) ob_clean();
	$respuesta = is_array($datos) ? (isset($datos[0]['OP']) ? $datos[0]['OP'] : '1') : $datos;
	if ($respuesta == '1') {
		$this->usuario_session->registrarActividad('USUARIO_REACTIVADO', 'Reactivó al usuario ID: ' . $argumento);
	}
	echo trim((string)$respuesta);
	exit();	
}

public function cambiar_clave()
{
    if(isset($_POST['txt_nueva_clave']) && !empty($_POST['txt_nueva_clave'])) {
        $cadena = $this->usuario_session->getCurrentUser();
        $idusuario = $cadena['idmiembro'];
        $nueva_clave = password_hash($_POST['txt_nueva_clave'], PASSWORD_BCRYPT);
        
        $this->usuario->set("idusuario", $idusuario);
        $this->usuario->set("clave", $nueva_clave);
        
        if($this->usuario->cambiar_clave()) {
            $this->usuario_session->registrarActividad('CONTRASENA_CAMBIADA', 'El usuario actualizó su contraseña de acceso.');
            echo json_encode(['status' => 'success', 'message' => 'Su contraseña ha sido actualizada correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la contraseña.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'La contraseña no puede estar vacía.']);
    }
    exit();
}
	
} // fin clase
?>