<?php namespace Config;



	class Core{


		public static function run(Request $request){
			$controlador = $request->getControlador() . "Controller";

			$ruta = ROOT . "Controllers" . DS . $controlador .".php";
			
			$metodo = $request->getMetodo();
			$argumento = $request->getArgumento();
			
	

			if(is_readable($ruta)){
				require_once $ruta;
			
				$mostrar = $controlador;
				$controlador = new $mostrar;
			
			if(method_exists($controlador, $metodo))
			{
				if(!isset($argumento)){ 
					$datos = call_user_func(array($controlador, $metodo));
				}else{
					
					//echo "<br> en la verificacion ingresa a la validacion: " . $argumento ."<br>";
					//$datos = call_user_func_array(array($controlador, $metodo), $argumento);
					$datos=$controlador->$metodo($argumento);

				}

                //Cargar vista
			
			
				$ruta = ROOT . "Views" . DS . $request->getControlador() . DS . $request->getMetodo() . ".php";

				if ($request->getMetodo()=="index") // validacion para cargar solo la vivsta index de cada controlador
				{	

				if(is_readable($ruta)){

					require_once $ruta;
					
			    }else{
					print "<br> Vista No Disponible";
				}
			} // fin de validacion de carga de solo la vista index
			else
			{ die();
			}


			} else
			 echo "<br> Método no valido";

			}
   			else
			{
				echo "<h2> Recurso No Disponible </h2>".URL;
				header('Location:'. URL . "inicio");
			}


		} // fin function run

	} // fin clase
?>