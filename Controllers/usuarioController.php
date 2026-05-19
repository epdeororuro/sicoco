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
				$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	        	echo $respuesta;
	        	exit();
			}
		}
		else
			{
				$respuesta="Error al enviar los Datos";
			}
   	      echo $respuesta;
   	      exit();
	}

public function edit()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_descripcion'];
			if (empty($_POST['txt_idusuario'])||
				empty($_POST['txt_nombre'])||
				empty($_POST['txt_usuario'])||
				empty($_POST['txt_clave'])||
				empty($_POST['txt_idrol']))
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
				$this->usuario->set("idusuario", $_POST['txt_idusuario']);
				$this->usuario->set("nombre", $_POST['txt_nombre']);
				$this->usuario->set("usuario", $_POST['txt_usuario']);
				$this->usuario->set("clave", password_hash($_POST['txt_clave'], PASSWORD_BCRYPT));
				$this->usuario->set("idrol", $_POST['txt_idrol']);
				
	        	$datos=$this->usuario->edit();	
				$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	        	echo $respuesta;
	        	exit();
			}
		}
		else
			{
				$respuesta="Error al enviar los Datos";
			}
   	      echo $respuesta;
   	      exit();
	}

public function delete($argumento)
{  $respuesta="valor inicial";
	$this->usuario->set("idusuario", $argumento);	
	$datos=$this->usuario->del();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta;
	exit();	
}

public function baja($argumento)
{  $respuesta="valor inicial";
	$this->usuario->set("idusuario", $argumento);	
	$datos=$this->usuario->baja();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta;
	exit();	
}

public function reactivar($argumento)
{  $respuesta="valor inicial";
	$this->usuario->set("idusuario", $argumento);	
	$datos=$this->usuario->reactivar();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta;
	exit();	
}
	
} // fin clase
?>