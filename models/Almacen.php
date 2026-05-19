<?php namespace Models;
	
	class Almacen{

		private $descripcion;
		private $ubicacion;
		private $contactos;
		private $tipo;
		private $activo;
		private $idalmacen;

		private $con;

		public function __construct(){
			$this->con = new Conexion();
		}

		public function set($atributo, $contenido){
			$this->$atributo = $contenido;
		}

		public function get($atributo){
			return $this->$atributo;
		}

		public function lst(){
		    $sql = "SELECT * FROM almacen WHERE TIPO LIKE 'ALMACEN' OR TIPO LIKE 'TIENDA' ORDER BY ACTIVO, TIPO";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
			/* se tiene tipo (almacen, tienda) las variables auxiliares son (pedido, venta)
			  con registro interno en la base de datos
			*/
		}

		public function add(){
			$sql = "INSERT INTO almacen (DESCRIPCION, UBICACION, CONTACTOS, TIPO) 
			VALUES (UPPER('{$this->descripcion}'), UPPER('{$this->ubicacion}'), 
			        '{$this->contactos}', UPPER('{$this->tipo}')";
			$retorno= $this->con->consultaSimple($sql);
            return $retorno;			
		}

		public function edit()
		{
			$sql="UPDATE ALMACEN SET DESCRIPCION =UPPER('{$this->descripcion}'), 
			                         UBICACION=UPPER('{$this->ubicacion}'), 
			                         CONTACTOS='{$this->contactos}', 
			                         TIPO= UPPER('{$this->tipo}')
			      WHERE IDALMACEN='{$this->idalmacen}'";
			$retorno= $this->con->consultaSimple($sql);
			return $retorno;
		}

		public function del()
		{
			$sql="call SP_DEL_ALMACEN('{$this->idalmacen}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

	}

//
//$a=new Almacen();
//echo json_encode($a->lst());
?>