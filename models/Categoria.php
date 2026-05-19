<?php namespace Models;
	
	class Categoria{

		private $descripcion;
		private $idcategoria;
		private $vigente;
		private $tipo;

		private $operacion;

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
		    $sql = "SELECT * FROM CATEGORIA WHERE VIGENTE LIKE '{$this->vigente}' AND TIPO LIKE '{$this->tipo}' ORDER BY VIGENTE ";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;			
		}

		public function lst_componente(){
		    $sql = "SELECT * FROM VISTA_DETALLE_ENSAMBLE WHERE IDCATEGORIA = '{$this->idcategoria}' AND TIPO NOT LIKE 'VACIO' ORDER BY COMPONENTE";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;			
		}

		public function add(){
			//$sql = "INSERT INTO CATEGORIA (DESCRIPCION, TIPO) 
		//	VALUES (UPPER('{$this->descripcion}'),UPPER('{$this->tipo}') )";
			$sql = "CALL SP_INSERT_CATEGORIA('{$this->descripcion}', '{$this->tipo}')";
			//CALL SP_INSERT_CATEGORIA('GESTION 2021, EQUIPO CORE I 6','ENSAMBLE')
			$retorno= $this->con->consultaSimple($sql);
           return $retorno;
           // return $sql;

		}

		public function edit()
		{
			$sql="UPDATE CATEGORIA SET DESCRIPCION =UPPER('{$this->descripcion}')
			      WHERE IDCATEGORIA='{$this->idcategoria}'";
			$retorno= $this->con->consultaSimple($sql);
			return $retorno;
		}

		public function del()
		{
			$sql="call SP_DEL_CATEGORIA('{$this->idcategoria}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function retirar()
		{
			$sql="call SP_RETIRA_CATEGORIA('{$this->idcategoria}', '{$this->operacion}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}
		

	}


?>