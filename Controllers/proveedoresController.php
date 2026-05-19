<?php
use Models\Personal as Personal;
use Config\sessionController as SessionController;
class proveedoresController
{
	private $proveedor;
	public function __construct()
	{
		
	//	$this->usuario_session=new SessionController();
	//	if ($this->usuario_session->verifica()) {
             $this->proveedor=new Personal();
		
	//	   }
	//	else
	//	{
		//	header('Location:'. URL . "login");
	//	}

		
	}
	
	public function index()
	{
		//$datos=$this->proveedor->lst();
		//return $datos;
	}

	public function listar()
	{
	  $this->proveedor->set("tipo_persona", "PROVEEDOR");
	  $datos=$this->proveedor->lst();
	  echo json_encode($datos);
	  exit();
	}

public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_descripcion'];
			if (empty($_POST['txt_nombres'])||
				empty($_POST['txt_apellidos'])||
				empty($_POST['txt_razon_social'])||
				empty($_POST['txt_telefonos'])||
				empty($_POST['txt_correo'])||
				empty($_POST['txt_direccion'])) 
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
				$this->proveedor->set("nombres", $_POST['txt_nombres']);
				$this->proveedor->set("apellidos", $_POST['txt_apellidos']);
				$this->proveedor->set("razon_social", $_POST['txt_razon_social']);
				$this->proveedor->set("telefonos", $_POST['txt_telefonos']);
				$this->proveedor->set("correo", $_POST['txt_correo']);
				$this->proveedor->set("direccion", $_POST['txt_direccion']);

				$this->proveedor->set("ci_nit", "0");
				$this->proveedor->set("tipo_documento", "-");
				$this->proveedor->set("tipo_persona", "PROVEEDOR");
				
				$datos=$this->proveedor->add();	
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
				empty($_POST['txt_razon_social'])||
				empty($_POST['txt_telefonos'])||
				empty($_POST['txt_correo'])||
				empty($_POST['txt_direccion'])) 
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
				$this->proveedor->set("idpersona", $_POST['txt_idpersona']);
				$this->proveedor->set("nombres", $_POST['txt_nombres']);
				$this->proveedor->set("apellidos", $_POST['txt_apellidos']);
				$this->proveedor->set("razon_social", $_POST['txt_razon_social']);
				$this->proveedor->set("telefonos", $_POST['txt_telefonos']);
				$this->proveedor->set("correo", $_POST['txt_correo']);
				$this->proveedor->set("direccion", $_POST['txt_direccion']);

				$this->proveedor->set("ci_nit", "0");
				$this->proveedor->set("tipo_documento", "-");
				$this->proveedor->set("tipo_persona", "PROVEEDOR");
				
				$datos=$this->proveedor->edit();	
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
	$this->proveedor->set("idpersona", $argumento);	
	$datos=$this->proveedor->del();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta; exit();	
}
	
} // fin clase
?>