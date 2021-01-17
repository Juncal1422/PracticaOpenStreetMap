<?php
// Activamos las variables de sesión.
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

// Rutas path de inc y otras..
define ('DS',DIRECTORY_SEPARATOR);

/* dirname(__FILE__) */
$rutabase=dirname(dirname(__FILE__)) .DS; 


$config['inc'] = $rutabase.'inc'.DS;
$config['class'] = $rutabase.'class'.DS;

// Constantes de configuración de la aplicación.
define ('DB_SERVIDOR','localhost');
define ('DB_PUERTO','3306');
define ('DB_BASEDATOS','Playas');
define ('DB_USUARIO','*****');
define ('DB_PASSWORD','*****');


// Cargamos la clase de base de datos
require_once 'basedatos.php';
?>