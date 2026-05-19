<?php
use Models\Categoria as Categoria;
use Config\sessionController as SessionController;
class catensambleController
{
	private $categoria;
	private $usuario_session;
	public function __construct()
	{
		
		$this->usuario_session=new SessionController();
		if ($this->usuario_session->verifica()) {
             $this->categoria=new Categoria();
		
		   }
		else
		{
			header('Location:'. URL . "login");
			exit();
		}

		
	}
	
	public function index()
	{
		//$datos=$this->categoria->lst();
		//return $datos;
	}

	public function listar()
	{
	  $this->categoria->set("vigente", "%");
	  $this->categoria->set("tipo", "ENSAMBLE");
	  $datos=$this->categoria->lst();
	  
	  echo json_encode($datos);
	  exit();
	}

	public function listar_componente($parametro)
	{
	  $this->categoria->set("idcategoria", $parametro);
	  $datos=$this->categoria->lst_componente();
	  
	  echo json_encode($datos);
	  exit();
	}

public function add()
	{$respuesta="valor inicial";		
		if($_POST){			
			if (empty($_POST['txt_descripcion'])) {
				$respuesta="Debe Completar los Datos";				
			}
			else 
			{
				$this->categoria->set("descripcion", $_POST['txt_descripcion']);
				$this->categoria->set("tipo", "ENSAMBLE");
				$datos=$this->categoria->add();			
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
			if (empty($_POST['txt_idcategoria'])||
				empty($_POST['txt_descripcion'])) {
				$respuesta="Debe Completar los Datos";				
			}
			else 
			{
				$this->categoria->set("idcategoria", $_POST['txt_idcategoria']);
				$this->categoria->set("descripcion", $_POST['txt_descripcion']);
				$datos=$this->categoria->edit();				
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
	$this->categoria->set("idcategoria", $argumento);	
	$datos=$this->categoria->del();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta; exit();	
}

public function retirar($argumento)
{   $respuesta="valor inicial";
	$this->categoria->set("idcategoria", $argumento);	
	$this->categoria->set("operacion", "RETIRAR");
	$datos=$this->categoria->retirar();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta; exit();	
}
public function habilitar($argumento)
{   $respuesta="valor inicial";
	$this->categoria->set("idcategoria", $argumento);
	$this->categoria->set("operacion", "HABILITAR");	
	$datos=$this->categoria->retirar();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta; exit();	
}	

} // fin clase
?>