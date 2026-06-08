<?php
use Models\Area as Area;
use Config\sessionController as SessionController;
class areaController
{
	private $area;
	private $usuario_session;

	public function __construct()
	{
		
		$this->usuario_session=new SessionController();
		if ($this->usuario_session->verifica()) {
             $this->area=new Area();
		
		   }
		else
		{
			header('Location:'. URL . "login");
			exit();
		}

		
	}
	
	public function index()
	{
		//$datos=$this->area->lst();
		//return $datos;
	}

	public function listar()
	{
	  $datos=$this->area->lst();
	  echo json_encode($datos);
	  exit();
	}

public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_nombre'];
			if (empty($_POST['txt_referencia'])||
				empty($_POST['txt_ubicacion']))				
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
			 $this->area->set("referencia", $_POST['txt_referencia']);
			 $this->area->set("ubicacion", $_POST['txt_ubicacion']);
								
			 $datos=$this->area->add();	
			 $respuesta = is_array($datos) ? (empty($datos) ? '1' : (isset($datos[0]) && is_array($datos[0]) ? array_values($datos[0])[0] : array_values($datos)[0])) : (($datos === null || $datos === '' || $datos === true) ? '1' : $datos);
	         if (ob_get_length()) ob_clean();
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
			
			if (empty($_POST['txt_idarea'])||
				empty($_POST['txt_referencia'])||
				empty($_POST['txt_ubicacion']))
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
			 $this->area->set("idarea", $_POST['txt_idarea']);
			 $this->area->set("referencia", $_POST['txt_referencia']);
			 $this->area->set("ubicacion", $_POST['txt_ubicacion']);
				
	         $datos=$this->area->edit();	
			 $respuesta = is_array($datos) ? (empty($datos) ? '1' : (isset($datos[0]) && is_array($datos[0]) ? array_values($datos[0])[0] : array_values($datos)[0])) : (($datos === null || $datos === '' || $datos === true) ? '1' : $datos);
	          if (ob_get_length()) ob_clean();
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
	$this->area->set("idarea", $argumento);	
	$datos=$this->area->del();
	$respuesta = is_array($datos) ? (empty($datos) ? '1' : (isset($datos[0]) && is_array($datos[0]) ? array_values($datos[0])[0] : array_values($datos)[0])) : (($datos === null || $datos === '' || $datos === true) ? '1' : $datos);
	if (ob_get_length()) ob_clean();
	echo trim((string)$respuesta);
	exit();
}
	
} // fin clase
?>