<?php  namespace Models;
use \PDO, \PDOException;
class Conexion{

 private $servidor="mysql:dbname=sicoco; host=127.0.0.1";
 private $usuario= "root";
 private $password="";
 private $pdo;

 public function __construct()
 { 
   try{
    	$this->pdo= new PDO($this->servidor, $this->usuario, $this->password);
    //	echo "conexion correcta a la base de datos...";
	  }catch(PDOException $e)
	  {
		echo "Error en la conexion.****.(".$e->getMessage();
	  }
 }

 public function ConsultaSimple($sql)
 {// ejecuta los insert, update y delete
  try {
  $consulta=$this->pdo->prepare($sql);
  $consulta->execute();
  $retorno=1;  
  } catch (\Exception $e) {
    $retorno=0;
  }
  return $retorno;
  
 }

 public function ConsultaRetorno($sql)
 {// ejecuta una consulta co retorno de listado de datos
 	$consulta=$this->pdo->prepare($sql);
 	$consulta->execute();
 	$lista=$consulta->fetchAll(PDO::FETCH_ASSOC);
 	
 	return $lista;

 }
}


//$a=new Models\Conexion();
//$a=new Conexion();
//print_r($a->ConsultaRetorno("select * from almacen"));

?>