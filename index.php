<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . DS);
$script_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('URL', sprintf("%s://%s%s/", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['HTTP_HOST'], $script_path === '/' ? '' : $script_path));




 require_once "Config/Autoload.php";
Config\Autoload::run();




ob_start();


// Iniciar sesión para control global de inactividad
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- VALIDACIÓN DE INACTIVIDAD (30 Minutos = 1800 segundos) ---
$inactividad_maxima = 1800; 
if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_inactivo = time() - $_SESSION['ultimo_acceso'];
    if ($tiempo_inactivo > $inactividad_maxima) {
        session_unset();
        session_destroy();
    } else {
        $_SESSION['ultimo_acceso'] = time(); // Renovar tiempo de actividad
    }
}

 $dir=new Config\Request();
 $controlador_actual = strtolower($dir->getControlador());

 // --- MIDDLEWARE DE VALIDACIÓN CSRF GLOBAL ---
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && $controlador_actual !== 'login') {
     $token = null;
     if (isset($_POST['csrf_token'])) {
         $token = $_POST['csrf_token'];
     } elseif (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
         $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
     }
     
     $session = new Config\sessionController();
     if (!$session->validarCSRF($token)) {
         http_response_code(403);
         if (ob_get_length()) ob_clean();
         echo json_encode(['status' => 'error', 'message' => 'Acceso denegado: Token CSRF no válido o expirado. Por favor, recargue la página.']);
         exit();
     }
 }

 require_once "views/template.php";


/*
$controller= $dir->getControlador();
$method= $dir->getMetodo();
$param= $dir->getArgumento();
 
 echo "el controlador = " . $controller;
 echo "*****el metodo = " . $method ;
 echo "*****el argumento = " . $param;
 echo "<br>";
*/


 Config\Core::run($dir);
/*
 echo "<br> la ruta del servidor es: " . $_SERVER['REQUEST_URI'];
 echo "<br> la ruta del documento es: " . $_SERVER['DOCUMENT_ROOT'];
echo "<br> el ROOT del proyecto es" . ROOT;
*/

ob_end_flush();
 ?>
