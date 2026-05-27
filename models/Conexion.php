<?php namespace Models; // Ajusta el namespace si tu archivo está en Config\

class Conexion {

    private $host = 'localhost';
    private $db   = 'sicoco'; // O 'arriendos' dependiendo de la base de datos activa
    private $user = 'root';
    private $pass = '';

    // Debe ser pública para que Login.php pueda hacer: $this->con->conexion->prepare()
    public $conexion;

    public function __construct() {
        try {
            // 1. DSN (Data Source Name)
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
            
            // 2. Opciones de configuración de PDO
            $opciones = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de error SQL
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,       // Devuelve arrays asociativos por defecto
                \PDO::ATTR_EMULATE_PREPARES   => false,                   // Falsa emulación = máxima seguridad contra Inyección SQL
            ];

            // 3. Crear la instancia de PDO
            $this->conexion = new \PDO($dsn, $this->user, $this->pass, $opciones);

        } catch (\PDOException $e) {
            // En producción, es mejor registrar en un archivo (log) en lugar de mostrar en pantalla
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    /* =========================================================================
       MÉTODOS DE RETROCOMPATIBILIDAD (LEGACY)
       Mantenemos estos métodos para que los modelos que aún no usan
       sentencias preparadas sigan funcionando hasta que los migres.
       ========================================================================= */

    // Método para INSERT, UPDATE, DELETE de modelos no migrados
    public function consultaSimple($sql) {
        try {
            $this->conexion->exec($sql);
        } catch (\PDOException $e) {
            die("Error en consulta simple: " . $e->getMessage());
        }
    }

    // Método para SELECT o CALL de modelos no migrados
    public function consultaRetorno($sql) {
        try {
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Error en consulta retorno: " . $e->getMessage());
        }
    }
}
?>
