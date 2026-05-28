<?php
use Models\Pagos as Pagos;
use Config\sessionController as SessionController;
class pagosController
{
	private $pagos;
	private $usuario_session;
	public function __construct()
	{
		
		$this->usuario_session=new SessionController();
		if ($this->usuario_session->verifica()) {
             $this->pagos=new Pagos();
		
		   }
	else
		{
			header('Location:'. URL . "login");
			exit();
		}
		
	}
	
	public function index()
	{
		//$datos=$this->pagos->lst();
		//return $datos;
	}

	public function listar()
	{
	  $datos=$this->pagos->lst();
	  echo json_encode($datos);
	  exit();
	}

	public function listar_pagos($argumento)
	{
	  $this->pagos->set("idarriendo", $argumento);	
	  $datos=$this->pagos->lst_pagos();
	  echo json_encode($datos);
	  exit();
	}

public function listar_detalle($argumento)
	{
	  $this->pagos->set("idarriendo", $argumento);	
	  $datos=$this->pagos->lst_detalle();
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
				$this->pagos->set("nombre", $_POST['txt_nombre']);
				$this->pagos->set("cedula", $_POST['txt_cedula']);
			$this->pagos->set("contactos", $_POST['txt_contactos']);
			$this->pagos->set("direccion", $_POST['txt_direccion']);
								
				$datos=$this->pagos->add();	
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

	public function plan_pagos($argumento)
	{
	  try {
	      $this->pagos->set("idarriendo", $argumento);	
	      $datos = $this->pagos->plan_pagos();
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'success', 'data' => $datos]);
	  } catch (\Exception $e) {
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
	  }
	  exit();
	}

	public function realizar_pago($idpago)
	{
	    try {
	        $this->pagos->set("idpago", $idpago);
            $cadena = $this->usuario_session->getCurrentUser();
	        $this->pagos->set("usr", $cadena['nombre']);
	        $this->pagos->registrar_pago();
	        if (ob_get_length()) ob_clean();
	        echo json_encode(['status' => 'success', 'message' => 'Pago realizado correctamente']);
	    } catch (\Exception $e) {
	        if (ob_get_length()) ob_clean();
	        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
	    }
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
				$this->pagos->set("idcliente", $_POST['txt_idcliente']);
				$this->pagos->set("nombre", $_POST['txt_nombre']);
				$this->pagos->set("cedula", $_POST['txt_cedula']);
			$this->pagos->set("contactos", $_POST['txt_contactos']);
			$this->pagos->set("direccion", $_POST['txt_direccion']);
				
	        	$datos=$this->pagos->edit();	
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
	$this->pagos->set("idcliente", $argumento);	
	$datos=$this->pagos->del();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta;	
	exit();	
}
	
} // fin clase
?>