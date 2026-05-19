<?php
/**
 * 
 */
use Config\sessionController as SessionController;

class inicioController
{  private $usuario_session;
	public function __construct()
	{
		//SE VERIFICA SI SE TIENE SESSION
	$this->usuario_session=new SessionController();
		
		if ($this->usuario_session->verifica()) 
			{ 
				//echo "el resultado de la sesion es:  ";
				//echo $this->usuario_session->getStatus();
				//print_r($this->usuario_session->getCurrentUser()) ;
				//$cadena= $this->usuario_session->getCurrentUser();
			//	print_r( $cadena['nombre']);
			  }
		else
		{
			header('Location:'. URL . "login");
			exit();
		}
	}
	
	public function index()
	{
		//echo "<br> Sistema integrado para -----control de calidad y Asistencia Tecnica";
		//header('Location:'. URL . "login");


	}

	public function inicio()
	{
		//echo "<br> saludos, el metodo inicio";
	}

}
?>