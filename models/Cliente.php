<?php namespace Models;
	
	class Cliente{

		private $idcliente;
		private $nombre;
		private $cedula;
		private $contactos;
		private $direccion;
		

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
			$sql = "SELECT IDCLIENTE, NOMBRE_COMPLETO AS NOMBRE, CEDULA, CONTACTOS, DIRECCION 
			        FROM clientes ORDER BY NOMBRE_COMPLETO";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}

		public function add(){
			$sql="CALL SP_INSERT_CLIENTE ('{$this->nombre}',
			'{$this->cedula}', '{$this->contactos}', '{$this->direccion}')";
			$datos=$this->con->consultaRetorno($sql);
			return $datos;			
		}

		public function del()
		{
			$sql="call SP_DEL_CLIENTE('{$this->idcliente}')";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}		

		public function edit(){
			$sql="CALL SP_MOD_CLIENTE
			      ({$this->idcliente},'{$this->nombre}',
			      '{$this->cedula}','{$this->contactos}',
			      '{$this->direccion}');";
			$datos = $this->con->ConsultaRetorno($sql);
			return $datos;
		}
		public function buscar_ci($cedula)
		{
			$sql = "SELECT * FROM clientes WHERE CEDULA = ? LIMIT 1";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$cedula]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		
	}

?>