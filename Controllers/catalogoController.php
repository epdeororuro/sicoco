<?php
use Models\Catalogo as Catalogo;
use Config\sessionController as SessionController;
class catalogoController
{
	private $catalogo;
	private $usuario_session;
	public function __construct()
	{
		
		$this->usuario_session=new SessionController();
		if ($this->usuario_session->verifica()) {
             $this->catalogo=new Catalogo();
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
	  $datos=$this->catalogo->lst();
	  echo json_encode($datos);
	  exit();
	}
	public function listar_areas()
	{
	 // genera el listado para mostrarlo en un componente select2
	  $datos=$this->catalogo->lst_areas();
	  echo json_encode($datos);
	  exit();
	}
public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['SelBuscarArea']."->".$_POST['txt_descripcion'];
			if (($_POST['SelBuscarArea']=='0')||
				empty($_POST['txt_descripcion'])||
				empty($_POST['txt_alquiler']))			
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios".$_POST['SelBuscarArea'];			
			}
			else 
			{
			 $this->catalogo->set("idarea", $_POST['SelBuscarArea']);
		$this->catalogo->set("descripcion", $_POST['txt_descripcion']);
			 $this->catalogo->set("alquiler", $_POST['txt_alquiler']);
								
			 $datos=$this->catalogo->add();	

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
			
			if (empty($_POST['txt_idcatalogo'])||
				($_POST['SelBuscarArea']=='0')||
				empty($_POST['txt_descripcion'])||
				empty($_POST['txt_alquiler']))
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
			 $this->catalogo->set("idcatalogo", $_POST['txt_idcatalogo']);
			 $this->catalogo->set("idarea", $_POST['SelBuscarArea']);
		$this->catalogo->set("descripcion", $_POST['txt_descripcion']);
			 $this->catalogo->set("alquiler", $_POST['txt_alquiler']);
				
	         $datos=$this->catalogo->edit();	
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
	$this->catalogo->set("idcatalogo", $argumento);	
	$datos=$this->catalogo->del();
	$respuesta = is_array($datos) ? (empty($datos) ? '1' : (isset($datos[0]) && is_array($datos[0]) ? array_values($datos[0])[0] : array_values($datos)[0])) : (($datos === null || $datos === '' || $datos === true) ? '1' : $datos);
	if (ob_get_length()) ob_clean();
	echo trim((string)$respuesta);	
	exit();	
}
	
} // fin clase
?>