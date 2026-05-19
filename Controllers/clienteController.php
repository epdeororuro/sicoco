<?php
use Models\Cliente as Cliente;
use Config\sessionController as SessionController;
class clienteController
{
	private $cliente;
	private $usuario_session;
	public function __construct()
	{
		
		$this->usuario_session=new SessionController();
	if ($this->usuario_session->verifica()) {
             $this->cliente=new Cliente();
		
		   }
		else
		{
			header('Location:'. URL . "login");
			exit();
		}
		
	}
	
	public function index()
	{
		//$datos=$this->cliente->lst();
		//return $datos;
	}

	public function listar()
	{
	  $datos=$this->cliente->lst();
	  echo json_encode($datos);
	  exit();
	}

public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_nombre'];
			if (empty($_POST['txt_nombre'])||
				empty($_POST['txt_cedula'])||
				empty($_POST['txt_contactos'])||
				empty($_POST['txt_direccion']))				
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
				$this->cliente->set("nombre", $_POST['txt_nombre']);
				$this->cliente->set("cedula", $_POST['txt_cedula']);
			$this->cliente->set("contactos", $_POST['txt_contactos']);
			$this->cliente->set("direccion", $_POST['txt_direccion']);
								
				$datos=$this->cliente->add();	
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
			if (empty($_POST['txt_idcliente'])||
				empty($_POST['txt_nombre'])||
				empty($_POST['txt_cedula'])||
				empty($_POST['txt_contactos'])||
				empty($_POST['txt_direccion']))	
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
				$this->cliente->set("idcliente", $_POST['txt_idcliente']);
				$this->cliente->set("nombre", $_POST['txt_nombre']);
				$this->cliente->set("cedula", $_POST['txt_cedula']);
			$this->cliente->set("contactos", $_POST['txt_contactos']);
			$this->cliente->set("direccion", $_POST['txt_direccion']);
				
	        	$datos=$this->cliente->edit();	
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
	$this->cliente->set("idcliente", $argumento);	
	$datos=$this->cliente->del();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta;	
	exit();	
}
	
} // fin clase
?>