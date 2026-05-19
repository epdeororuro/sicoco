<?php namespace Config;

class Request{
	private $controlador;
	private $metodo;
	private $argumento;

	public function __construct(){
		
		if(isset($_GET['url'])){

		        $ruta = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
                $ruta = explode("/", $ruta);
                $ruta = array_filter($ruta);                                                                                   
                $this->controlador=strtolower($ruta[0]);

                if(isset($ruta[1]))
                {// si existe un metodo en la posicion[1]
                	if(strtolower($ruta[1])=="index.php")
                		{
                			$this->metodo="index";
                		}
                	else{

                	$this->metodo=strtolower($ruta[1]);
                       }
                } 
                else
                {
                	$this->metodo="index";
                }

                if(isset($ruta[2]))
                {// si existe un valor en la posicion[2]
                	$this->argumento=strtolower($ruta[2]);
                }
                
        } // fin de if
        else{
        	  	$this->controlador="inicio";
        	  	$this->metodo="index";
        	
        	//echo "sin direccion";

        }         
	}

	public function getControlador()
	{
		return $this->controlador;
	}

	public function getMetodo()
	{
		return $this->metodo;
	}

	public function getArgumento()
	{
		return $this->argumento;
	}

}
?>