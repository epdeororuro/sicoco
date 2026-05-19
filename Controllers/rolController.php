<?php
use Models\Rol as Rol;
use Config\sessionController as SessionController;
class rolController
{
	private $rol;
	public function __construct()
	{
		
	//	$this->usuario_session=new SessionController();
	//	if ($this->usuario_session->verifica()) {
             $this->rol=new Rol();
		
	//	   }
	//	else
	//	{
		//	header('Location:'. URL . "login");
	//	}

		
	}
	
	public function index()
	{
		//$datos=$this->rol->lst();
		//return $datos;
	}

	public function listar()
	{
	 // $this->rol->set("tipo_persona", "OPERADOR");
	  $datos=$this->rol->lst();
	  echo json_encode($datos);
	  exit();
	}
	public function listar_operador()
	{
	 // genera el listado del personal para mostrarlo en un componente select2
	  $datos=$this->rol->lst_operador();
	  echo json_encode($datos);
	  exit();
	}

public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_descripcion'];
			if (($_POST['SelBuscarPersona']=='0')||
				empty($_POST['txt_usuario'])||
				($_POST['txt_rol'])=='0') 
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{   $parametro=explode("-", $_POST['SelBuscarPersona']);
				$id=$parametro[0];
				$clave=password_hash($parametro[1], PASSWORD_BCRYPT) ;
				//$this->rol->set("idpersona", $_POST['SelBuscarPersona']);
				$this->rol->set("idpersona",$id);
				$this->rol->set("usuario", $_POST['txt_usuario']);
				$this->rol->set("rol", $_POST['txt_rol']);
				$this->rol->set("clave", $clave);

				$datos=$this->rol->add();
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
		$this->rol->set("idencargado", $argumento);	
		$datos=$this->rol->del();
		$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
		echo $respuesta;
		exit();	
	}

	public function baja($argumento)
	{  $respuesta="valor inicial";
		$this->rol->set("idencargado", $argumento);	
		$respuesta=$this->rol->baja();
		//$datos=$this->rol->baja();
		// foreach ($datos as $lst_rol)

		//$respuesta=$lst_rol['OP'];	
		echo $respuesta;
		exit();
	}
/*
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
				$this->rol->set("idpersona", $_POST['txt_idpersona']);
				$this->rol->set("nombres", $_POST['txt_nombres']);
				$this->rol->set("apellidos", $_POST['txt_apellidos']);
				$this->rol->set("ci_nit", $_POST['txt_cedula']);
				$this->rol->set("telefonos", $_POST['txt_telefonos']);
				$this->rol->set("correo", $_POST['txt_correo']);
				$this->rol->set("direccion", $_POST['txt_direccion']);

				$this->rol->set("razon_social", "-");
				$this->rol->set("tipo_documento", "CEDULA");
				$this->rol->set("tipo_persona", "OPERADOR");
				
				$respuesta=$this->rol->edit();	
	
	        	header('Location:'. URL . "views/publico/publico.php?respuesta=".$respuesta);			
			}

		}
		else
			{
				$respuesta="Error al enviar los Datos";
			}
   	      header('Location:'. URL . "views/publico/publico.php?respuesta=".$respuesta);
	}


	*/
} // fin clase
?>