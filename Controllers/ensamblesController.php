<?php
use Models\Articulo as Articulo;
use Config\sessionController as SessionController;
class ensamblesController
{
	private $ensamble;
	private $usuario_session;
	public function __construct()
	{
		
		$this->usuario_session=new SessionController();
		if ($this->usuario_session->verifica()) {
             $this->ensamble=new Articulo();
		
		   }
		else
		{
			header('Location:'. URL . "login");
			exit();
		}

		
	}
	
	public function index()
	{
		//$datos=$this->ensamble->lst();
		//return $datos;
	}

	public function listar()
	{
	  $this->ensamble->set("tipo", "ENSAMBLE");
	  $datos=$this->ensamble->lst();
	  echo json_encode($datos);
	  exit();
	}
	public function listar_categoria()
	{
	 // genera el listado del personal para mostrarlo en un componente select2
		$this->ensamble->set("tipo", "ENSAMBLE");
	  $datos=$this->ensamble->lst_categoria();
	  echo json_encode($datos);
	  exit();
	}

public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_descripcion'];
			if (($_POST['SelBuscarCategoria']=='0')||
				empty($_POST['txt_descripcion']))				
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{   
				$this->ensamble->set("idcategoria",$_POST['SelBuscarCategoria']);
				$this->ensamble->set("descripcion",$_POST['txt_descripcion']);
				$this->ensamble->set("codbarra","0");
				$this->ensamble->set("minimo","0");
				$this->ensamble->set("tipo","ENSAMBLE");
				$datos=$this->ensamble->add();
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
			if (empty($_POST['txt_descripcion'])||
				empty($_POST['txt_codigo_barra'])||
				empty($_POST['txt_stock'])) 
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{   
			    $this->ensamble->set("idarticulo", $_POST['txt_idarticulo']);
				$this->ensamble->set("descripcion",$_POST['txt_descripcion']);
				$this->ensamble->set("codbarra",$_POST['txt_codigo_barra']);
				$this->ensamble->set("minimo",$_POST['txt_stock']);
				
				$datos=$this->ensamble->edit();
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

	public function cambio()
{$respuesta="valor inicial";		
		if($_POST){
			if (($_POST['SelBuscarCategoria']=='0')||
				empty($_POST['txt_idarticulo'])) 
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{   
				$this->ensamble->set("idcategoria",$_POST['SelBuscarCategoria']);
				 $this->ensamble->set("idarticulo", $_POST['txt_idarticulo']);

				$datos=$this->ensamble->cambio();
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
		$this->ensamble->set("idarticulo", $argumento);	
		$datos=$this->ensamble->del();
		$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
		echo $respuesta; exit();	
	}

	
} // fin clase
?>