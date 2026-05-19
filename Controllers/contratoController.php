<?php
use Models\Contrato as Contrato;
use Config\sessionController as SessionController;
class contratoController
{
	private $contrato;
	private $usuario_session;

	public function __construct()
	{
		
		$this->usuario_session=new SessionController();
		if ($this->usuario_session->verifica()) {
             $this->contrato=new Contrato();
		
		   }
		else
		{
			header('Location:'. URL . "login");
			exit();
		}
		
	}
	
	public function index()
	{
		//$datos=$this->contrato->lst();
		//return $datos;
	}

	public function listar()
	{
	  $datos=$this->contrato->lst();
	  echo json_encode($datos);
	  exit();

	}

	
	public function listar_clientes()
	{
	 // genera el listado para mostrarlo en un componente select2
	  $datos=$this->contrato->lst_clientes();

	  echo json_encode($datos);
	  exit();
	}

	
	public function listar_catalogo()
	{
	 // genera el listado para mostrarlo en un componente select2
	  $datos=$this->contrato->lst_catalogo();

	  echo json_encode($datos);
	  exit();
	}

	public function listar_detalle($argumento)
    {  
    //	$respuesta="valor inicial";
	$this->contrato->set("idcontrato", $argumento);	
	  $datos=$this->contrato->lst_detalle();

	  echo json_encode($datos);
	  exit();
	}


public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_nombre'];
			if (($_POST['SelBuscarCliente']=='0')||
				empty($_POST['txt_actividad'])||
				empty($_POST['txt_razon_social'])||
				empty($_POST['txt_contrato']) ||
		    	empty($_POST['txt_fecha_inicio'])||
		    	empty($_POST['txt_tiempo']))
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{
				$cadena= $this->usuario_session->getCurrentUser(); // saca el idusuario que se encuentra en la sesion
				$this->contrato->set("idcliente", $_POST['SelBuscarCliente']);
				$this->contrato->set("idusuario", $cadena['idmiembro']); 
				
				$this->contrato->set("actividad", $_POST['txt_actividad']);
				$this->contrato->set("razon_social", $_POST['txt_razon_social']);
				$this->contrato->set("contrato", $_POST['txt_contrato']);
			$this->contrato->set("fecha_inicio", $_POST['txt_fecha_inicio']);
			$this->contrato->set("tiempo_contrato", $_POST['txt_tiempo']);
								
				$datos=$this->contrato->add();	
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
			if (empty($_POST['txt_idcontrato'])||
				($_POST['SelBuscarCliente']=='0')||
				empty($_POST['txt_actividad'])||
				empty($_POST['txt_razon_social'])||
				empty($_POST['txt_contrato']) ||
		    	empty($_POST['txt_fecha_inicio'])||
		    	empty($_POST['txt_tiempo']))
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{ $cadena= $this->usuario_session->getCurrentUser(); // saca el idusuario que se encuentra en la sesion
			 $this->contrato->set("idcliente", $_POST['SelBuscarCliente']);
			 $this->contrato->set("idusuario", $cadena['idmiembro']);
			 
			 $this->contrato->set("idcontrato",$_POST['txt_idcontrato'] );
			 $this->contrato->set("actividad", $_POST['txt_actividad']);
			 $this->contrato->set("razon_social", $_POST['txt_razon_social']);
			 $this->contrato->set("contrato", $_POST['txt_contrato']);
			$this->contrato->set("fecha_inicio", $_POST['txt_fecha_inicio']);
			$this->contrato->set("tiempo_contrato", $_POST['txt_tiempo']);
				
	        	$datos=$this->contrato->edit();	
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
	$this->contrato->set("idcontrato", $argumento);	
	$datos=$this->contrato->del();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta;
	exit();	
}

public function addetalle($argumento)
{  $respuesta="valor inicial";
// se debe separar los valores de las variable (idcontrato-idcatalogo)
    $parametro=explode("-",$argumento );
	$this->contrato->set("idcontrato", $parametro[0]);	
	$this->contrato->set("idcatalogo", $parametro[1]);	
	$datos=$this->contrato->add_detalle();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta;
	exit();	
}

public function del_detalle($argumento)
{  $respuesta="valor inicial";
	$this->contrato->set("iddetalle", $argumento);	
	$datos=$this->contrato->delete_detalle();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta;
	exit();	
}


public function confirmar($argumento)
{  $respuesta="valor inicial";
	$this->contrato->set("idcontrato", $argumento);	
	$datos=$this->contrato->confirma_contrato();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	echo $respuesta;
	exit();	
}
	
} // fin clase
?>