<?php
use Models\Almacen as Almacen;
use Config\sessionController as SessionController;
class almacenController
{
	private $almacen;
	private $usuario_session;
	public function __construct()
	{
		
		$this->usuario_session=new SessionController();
		if ($this->usuario_session->verifica()) {
             $this->almacen=new Almacen();
		
		   }
		else
		{
			header('Location:'. URL . "login");
			exit();
		}

		
	}
	
	public function index()
	{
		//$datos=$this->almacen->lst();
		//return $datos;
	}

	public function listar()
	{
	  $datos=$this->almacen->lst();
	  echo json_encode($datos);
	  exit();
	}

public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_descripcion'];
			if (empty($_POST['txt_descripcion'])||
				empty($_POST['txt_ubicacion'])||
				empty($_POST['txt_contactos'])||
				$_POST['txt_tipo']=="Seleccione un Dato") {
				$respuesta="Debe Completar los Datos";				
			}
			else 
			{
				$this->almacen->set("descripcion", $_POST['txt_descripcion']);
				$this->almacen->set("ubicacion", $_POST['txt_ubicacion']);
				$this->almacen->set("contactos", $_POST['txt_contactos']);
				$this->almacen->set("tipo", $_POST['txt_tipo']);
				$datos=$this->almacen->add();			
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
			if (empty($_POST['txt_id'])||
				empty($_POST['txt_descripcion'])||
				empty($_POST['txt_ubicacion'])||
				empty($_POST['txt_contactos'])||
				$_POST['txt_tipo']=="Seleccione un Dato") {
				$respuesta="Debe Completar los Datos";				
			}
			else 
			{
				$this->almacen->set("idalmacen", $_POST['txt_id']);
				$this->almacen->set("descripcion", $_POST['txt_descripcion']);
				$this->almacen->set("descripcion", $_POST['txt_descripcion']);
				$this->almacen->set("ubicacion", $_POST['txt_ubicacion']);
				$this->almacen->set("contactos", $_POST['txt_contactos']);
				$this->almacen->set("tipo", $_POST['txt_tipo']);
				$datos=$this->almacen->edit();				
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
	$this->almacen->set("idalmacen", $argumento);	
	$datos=$this->almacen->del();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta; exit();	
}
	


} // fin clase
?>