<?php namespace Config;
/**
 * 
 */

class sessionController
{
	private $nombre;
	private $idmiembro;
	private $cargo;
	//private $correo;

	public function __construct()
	//public function iniciar()
	{
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
	}
	}

    public function set($atributo, $contenido){
			$this->$atributo = $contenido;
		}

	public function get($atributo){
			return $this->$atributo;
		}

	public function setCurrentUser()
	{
		$_SESSION['nombre']=$this->nombre;
		$_SESSION['idmiembro']=$this->idmiembro;
		$_SESSION['cargo']=$this->cargo;
		//$_SESSION['correo']=$this->correo;
	}

public function verifica()
	{
		if(isset($_SESSION['nombre'] ) && isset($_SESSION['idmiembro'] ) && isset($_SESSION['cargo'] ))
		return 1;
	else
		return 0;
	}

	public function getCurrentUser()
	{
		return $_SESSION;
	}

	public function closeSession()
	{
		session_unset();
		session_destroy();
	}

  public function getStatus()
  {
    return session_status();
  }

	public function index()
	{
	//	echo "<br> Sistema integrado para control de calidad y Asistencia Tecnica";
	//	header('Location:'. URL . "login");
	}

	
}
?>