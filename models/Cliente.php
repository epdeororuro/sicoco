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
			$stmt = $this->con->conexion->query($sql);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function add(){
			$sql="CALL SP_INSERT_CLIENTE (?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->nombre, $this->cedula, $this->contactos, $this->direccion]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function del()
		{
			$sql="CALL SP_DEL_CLIENTE(?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcliente]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}		

		public function edit(){
			$sql="CALL SP_MOD_CLIENTE(?, ?, ?, ?, ?)";
			$stmt = $this->con->conexion->prepare($sql);
			$stmt->execute([$this->idcliente, $this->nombre, $this->cedula, $this->contactos, $this->direccion]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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