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
	  try {
	      $datos = $this->contrato->lst();
	      if (ob_get_length()) ob_clean();
	      
	      $json = json_encode(['status' => 'success', 'data' => $datos]);
	      if ($json === false) {
	          echo json_encode(['status' => 'error', 'data' => [], 'message' => 'Error JSON: ' . json_last_error_msg()]);
	      } else {
	          echo $json;
	      }
	  } catch (\Exception $e) {
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
	  }
	  exit();
	}

	
	public function listar_clientes()
	{
	  try {
	      $datos = $this->contrato->lst_clientes();
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'success', 'data' => $datos]);
	  } catch (\Exception $e) {
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
	  }
	  exit();
	}

	
	public function listar_catalogo()
	{
	  try {
	      $datos = $this->contrato->lst_catalogo();
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'success', 'data' => $datos]);
	  } catch (\Exception $e) {
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
	  }
	  exit();
	}

	public function listar_areas()
	{
	  try {
	      $datos = $this->contrato->lst_areas();
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'success', 'data' => $datos]);
	  } catch (\Exception $e) {
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
	  }
	  exit();
	}

	public function listar_catalogo_por_area($idarea = null)
	{
	  // Previene errores si el ID no llega correctamente
	  if(!$idarea){
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'success', 'data' => []]);
	      exit();
	  }
	  
	  try {
	      $this->contrato->set("idarea", $idarea);
	      $datos = $this->contrato->lst_catalogo_por_area();
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'success', 'data' => $datos]);
	  } catch (\Exception $e) {
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
	  }
	  exit();
	}

	public function listar_detalle($argumento)
    {  
	  try {
	      $this->contrato->set("idcontrato", $argumento);	
	      $datos = $this->contrato->lst_detalle();
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'success', 'data' => $datos]);
	  } catch (\Exception $e) {
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
	  }
	  exit();
	}


public function add()
	{		
		if($_POST){
			// Verificación rigurosa de todos los campos del nuevo formulario
			if (empty($_POST['SelItemCatalogo']) || $_POST['SelItemCatalogo'] == '0' ||
				empty($_POST['txt_cedula']) ||
				empty($_POST['txt_nombres']) ||
				empty($_POST['txt_paterno']) ||
				empty($_POST['txt_celular']) ||
				empty($_POST['txt_direccion']) ||
				empty($_POST['txt_actividad'])||
				empty($_POST['txt_razon_social'])||
				empty($_POST['txt_contrato']) ||
				empty($_POST['txt_fecha_suscripcion']) ||
		    	empty($_POST['txt_fecha_inicio'])||
		    	empty($_POST['txt_tiempo']))
			{
				echo json_encode(['status' => 'error', 'message' => 'Debe Completar los Datos, Todos los Campos son Obligatorios']);				
			}
			else 
			{
				$cadena= $this->usuario_session->getCurrentUser(); // saca el idusuario que se encuentra en la sesion
				$this->contrato->set("idusuario", $cadena['idmiembro']); 
				
				// 1. Datos del Catálogo a Arrendar
				$this->contrato->set("idcatalogo", $_POST['SelItemCatalogo']);

				// 2. Datos Unificados del Cliente
				$this->contrato->set("cedula", $_POST['txt_cedula']);
				$this->contrato->set("nombres", $_POST['txt_nombres']);
				$this->contrato->set("paterno", $_POST['txt_paterno']);
				$this->contrato->set("materno", isset($_POST['txt_materno']) ? $_POST['txt_materno'] : '');
				$this->contrato->set("celular", $_POST['txt_celular']);
				$this->contrato->set("direccion", $_POST['txt_direccion']);

				// 3. Datos del Contrato
				$this->contrato->set("actividad", $_POST['txt_actividad']);
				$this->contrato->set("razon_social", $_POST['txt_razon_social']);
				$this->contrato->set("contrato", $_POST['txt_contrato']);
				$this->contrato->set("fecha_suscripcion", $_POST['txt_fecha_suscripcion']);
				$this->contrato->set("fecha_inicio", $_POST['txt_fecha_inicio']);
				$this->contrato->set("tiempo_contrato", $_POST['txt_tiempo']);
								
				// Llamada al nuevo método unificado
				$datos=$this->contrato->add_unified();	
				$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	        	if ($respuesta == '1') {
                    echo json_encode(['status' => 'success', 'message' => 'Registro insertado con éxito']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => $respuesta]);
                }
	        	exit();
			}
		}
		else
			{
				echo json_encode(['status' => 'error', 'message' => 'Error al enviar los Datos']);
			}
        exit();
	}

	public function obtener($argumento)
	{
	  try {
	      $this->contrato->set("idcontrato", $argumento);	
	      $datos = $this->contrato->obtener_contrato_completo();
	      if (ob_get_length()) ob_clean();
	      if ($datos && count($datos) > 0) {
              echo json_encode(['status' => 'success', 'data' => $datos[0]]);
          } else {
              echo json_encode(['status' => 'error', 'message' => 'Contrato no encontrado']);
          }
	  } catch (\Exception $e) {
	      if (ob_get_length()) ob_clean();
	      echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
	  }
	  exit();
	}

public function edit()
	{		
		if($_POST){
			if (empty($_POST['txt_idcontrato'])||
				empty($_POST['SelItemCatalogo']) || $_POST['SelItemCatalogo'] == '0' ||
				empty($_POST['txt_cedula']) ||
				empty($_POST['txt_nombres']) ||
				empty($_POST['txt_paterno']) ||
				empty($_POST['txt_celular']) ||
				empty($_POST['txt_direccion']) ||
				empty($_POST['txt_actividad'])||
				empty($_POST['txt_razon_social'])||
				empty($_POST['txt_contrato']) ||
				empty($_POST['txt_fecha_suscripcion']) ||
		    	empty($_POST['txt_fecha_inicio'])||
		    	empty($_POST['txt_tiempo']))
			{
				echo json_encode(['status' => 'error', 'message' => 'Debe Completar los Datos, Todos los Campos son Obligatorios']);				
			}
			else 
			{ 
				$this->contrato->set("idcontrato", $_POST['txt_idcontrato']);
				$this->contrato->set("idcatalogo", $_POST['SelItemCatalogo']);
				$this->contrato->set("cedula", $_POST['txt_cedula']);
				$this->contrato->set("nombres", $_POST['txt_nombres']);
				$this->contrato->set("paterno", $_POST['txt_paterno']);
				$this->contrato->set("materno", isset($_POST['txt_materno']) ? $_POST['txt_materno'] : '');
				$this->contrato->set("celular", $_POST['txt_celular']);
				$this->contrato->set("direccion", $_POST['txt_direccion']);
				$this->contrato->set("actividad", $_POST['txt_actividad']);
				$this->contrato->set("razon_social", $_POST['txt_razon_social']);
				$this->contrato->set("contrato", $_POST['txt_contrato']);
				$this->contrato->set("fecha_suscripcion", $_POST['txt_fecha_suscripcion']);
				$this->contrato->set("fecha_inicio", $_POST['txt_fecha_inicio']);
				$this->contrato->set("tiempo_contrato", $_POST['txt_tiempo']);
				
	        	$datos=$this->contrato->edit();	
				$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	        	if ($respuesta == '1') {
                    echo json_encode(['status' => 'success', 'message' => 'Registro modificado con éxito']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => $respuesta]);
                }
	        	exit();	
			}
		}
		else
			{
				echo json_encode(['status' => 'error', 'message' => 'Error al enviar los Datos']);
			}
        exit();
	}

public function delete($argumento)
{  
	$this->contrato->set("idcontrato", $argumento);	
	$datos=$this->contrato->del();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	if ($respuesta == '1') {
        echo json_encode(['status' => 'success', 'message' => 'Registro eliminado con éxito']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $respuesta]);
    }
	exit();	
}

public function addetalle($argumento)
{  
// se debe separar los valores de las variable (idcontrato-idcatalogo)
    $parametro=explode("-",$argumento );
	$this->contrato->set("idcontrato", $parametro[0]);	
	$this->contrato->set("idcatalogo", $parametro[1]);	
	$datos=$this->contrato->add_detalle();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	if ($respuesta == '1') {
        echo json_encode(['status' => 'success', 'message' => 'Detalle agregado con éxito']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $respuesta]);
    }
	exit();	
}

public function del_detalle($argumento)
{  
	$this->contrato->set("iddetalle", $argumento);	
	$datos=$this->contrato->delete_detalle();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	if ($respuesta == '1') {
        echo json_encode(['status' => 'success', 'message' => 'Detalle eliminado con éxito']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $respuesta]);
    }
	exit();	
}


public function confirmar($argumento)
{  
	$this->contrato->set("idcontrato", $argumento);	
	$datos=$this->contrato->confirma_contrato();
	$respuesta = (is_array($datos) && isset($datos[0]['OP'])) ? $datos[0]['OP'] : $datos;
	if ($respuesta == '1') {
        echo json_encode(['status' => 'success', 'message' => 'Contrato confirmado con éxito']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $respuesta]);
    }
	exit();	
}
	
} // fin clase
?>