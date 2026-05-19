<?php namespace Models;
	
	class Articulo{

		private $idarticulo;
		private $idcategoria;
		private $descripcion;
		private $minimo;
		private $codbarra;
		private $tipo;
		
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
			$sql = "SELECT * FROM VISTA_ARTICULO_CATEGORIA WHERE TIPO LIKE '{$this->tipo}' 
			        ORDER BY C_DESCRIPCION, DESCRIPCION";			        
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}
		public function lst_categoria(){
			/*$sql = "SELECT IDCATEGORIA, DESCRIPCION FROM CATEGORIA 
			        WHERE VIGENTE LIKE 'SI' AND TIPO LIKE '{$this->tipo}' ORDER BY DESCRIPCION"; 
*/
			  $sql = "SELECT IDAREA, concat(REFERENCIA, '->', UBICACION ) as DISTRIBUCION FROM areaubicacion 
			order by DISTRIBUCION";
			

			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function add()
		{			
			$sql="INSERT INTO ARTICULO(IDCATEGORIA, DESCRIPCION, MINIMO, CODBARRA, TIPO)
			VALUES ('{$this->idcategoria}', UPPER('{$this->descripcion}'), '{$this->minimo}',
			        '{$this->codbarra}', UPPER('{$this->tipo}'))";
			$datos=$this->con->consultaSimple($sql);
			return $datos;			
		}

		public function edit()
		{
			$sql="UPDATE ARTICULO SET DESCRIPCION=UPPER('{$this->descripcion}'),
			                          MINIMO='{$this->minimo}',
			                          CODBARRA='{$this->codbarra}'
			       WHERE IDARTICULO = '$this->idarticulo'";
			$datos=$this->con->consultaSimple($sql);
			return $datos;
		}

		public function cambio()
		{
			$sql="UPDATE ARTICULO SET IDCATEGORIA='{$this->idcategoria}'			                         
			       WHERE IDARTICULO = '$this->idarticulo'";
			$datos=$this->con->consultaSimple($sql);
			return $datos;
		}

		public function del()
		{
			$sql="call SP_DEL_ARTICULO('{$this->idarticulo}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

	}

?>