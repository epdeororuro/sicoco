<?php
use Models\Personal as Personal;
use Config\sessionController as SessionController;
class personalController
{
	private $personal;
	private $usuario_session;
	public function __construct()
	{
		
		$this->usuario_session=new SessionController();
		if ($this->usuario_session->verifica()) {
             $this->personal=new Personal();
		
		   }
		else
		{
			header('Location:'. URL . "login");
			exit();
		}

		
	}
	
	public function index()
	{
		//$datos=$this->personal->lst();
		//return $datos;
	}

	public function listar()
	{
	  $this->personal->set("tipo_persona", "OPERADOR");
	  $datos=$this->personal->lst();
	  echo json_encode($datos);
	  exit();
	}

public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_descripcion'];
			if (empty($_POST['txt_nombres'])||
				empty($_POST['txt_apellidos'])||
				empty($_POST['txt_cedula'])||
				empty($_POST['txt_telefonos'])||
				empty($_POST['txt_correo'])||
				empty($_POST['txt_direccion'])) 
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
				$this->personal->set("nombres", $_POST['txt_nombres']);
				$this->personal->set("apellidos", $_POST['txt_apellidos']);
				$this->personal->set("ci_nit", $_POST['txt_cedula']);
				$this->personal->set("telefonos", $_POST['txt_telefonos']);
				$this->personal->set("correo", $_POST['txt_correo']);
				$this->personal->set("direccion", $_POST['txt_direccion']);

				$this->personal->set("razon_social", "-");
				$this->personal->set("tipo_documento", "CEDULA");
				$this->personal->set("tipo_persona", "OPERADOR");
				
				$datos=$this->personal->add();	
				$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	        	echo $respuesta; exit();			
			}
		}
		else
			{
				$respuesta="Error al enviar los Datos";
			}
   	      echo $respuesta; exit();
	}

public function edit()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_descripcion'];
			if (empty($_POST['txt_idpersona'])||
				empty($_POST['txt_nombres'])||
				empty($_POST['txt_apellidos'])||
				empty($_POST['txt_cedula'])||
				empty($_POST['txt_telefonos'])||
				empty($_POST['txt_correo'])||
				empty($_POST['txt_direccion'])) 
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
				$this->personal->set("idpersona", $_POST['txt_idpersona']);
				$this->personal->set("nombres", $_POST['txt_nombres']);
				$this->personal->set("apellidos", $_POST['txt_apellidos']);
				$this->personal->set("ci_nit", $_POST['txt_cedula']);
				$this->personal->set("telefonos", $_POST['txt_telefonos']);
				$this->personal->set("correo", $_POST['txt_correo']);
				$this->personal->set("direccion", $_POST['txt_direccion']);

				$this->personal->set("razon_social", "-");
				$this->personal->set("tipo_documento", "CEDULA");
				$this->personal->set("tipo_persona", "OPERADOR");
				
				$datos=$this->personal->edit();	
				$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	        	echo $respuesta; exit();			
			}

		}
		else
			{
				$respuesta="Error al enviar los Datos";
			}
   	      echo $respuesta; exit();
	}

public function delete($argumento)
{  $respuesta="valor inicial";
	$this->personal->set("idpersona", $argumento);	
	$datos=$this->personal->del();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta; exit();	
}
	
} // fin clase
?>