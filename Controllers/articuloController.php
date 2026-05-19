<?php
use Models\Articulo as Articulo;
use Config\sessionController as SessionController;
class articuloController
{
	private $articulo;
	public function __construct()
	{
		
	//	$this->usuario_session=new SessionController();
	//	if ($this->usuario_session->verifica()) {
             $this->articulo=new Articulo();
		
	//	   }
	//	else
	//	{
		//	header('Location:'. URL . "login");
	//	}

		
	}
	
	public function index()
	{
		//$datos=$this->articulo->lst();
		//return $datos;
	}

	public function listar()
	{
	  $this->articulo->set("tipo", "ARTICULO");
	  $datos=$this->articulo->lst();
	  echo json_encode($datos);
	  exit();
	}
	public function listar_categoria()
	{
	 // genera el listado del personal para mostrarlo en un componente select2
		//$this->articulo->set("tipo", "ARTICULO");
	  $datos=$this->articulo->lst_categoria();
	  echo json_encode($datos);
	  exit();
	}

public function add()
	{$respuesta="valor inicial";		
		if($_POST){
			//echo "el valor enviado por post es-->".$_POST['txt_descripcion'];
			if (($_POST['SelBuscarCategoria']=='0')||
				empty($_POST['txt_descripcion'])||
				empty($_POST['txt_codigo_barra'])||
				empty($_POST['txt_stock'])) 
			{
				$respuesta="Debe Completar los Datos, Todos los Campos son Obligatorios";				
			}
			else 
			{   
				$this->articulo->set("idcategoria",$_POST['SelBuscarCategoria']);
				$this->articulo->set("descripcion",$_POST['txt_descripcion']);
				$this->articulo->set("codbarra",$_POST['txt_codigo_barra']);
				$this->articulo->set("minimo",$_POST['txt_stock']);
				$this->articulo->set("tipo","ARTICULO");
				$datos=$this->articulo->add();
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
			    $this->articulo->set("idarticulo", $_POST['txt_idarticulo']);
				$this->articulo->set("descripcion",$_POST['txt_descripcion']);
				$this->articulo->set("codbarra",$_POST['txt_codigo_barra']);
				$this->articulo->set("minimo",$_POST['txt_stock']);
				//$this->articulo->set("tipo","ARTICULO");
				$datos=$this->articulo->edit();
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
				$this->articulo->set("idcategoria",$_POST['SelBuscarCategoria']);
				 $this->articulo->set("idarticulo", $_POST['txt_idarticulo']);

				$datos=$this->articulo->cambio();
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
		$this->articulo->set("idarticulo", $argumento);	
		$datos=$this->articulo->del();
		$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
		echo $respuesta; exit();	
	}

	
} // fin clase
?>