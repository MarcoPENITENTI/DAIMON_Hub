<?php
// Configurazione di base
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Definisci le costanti di base
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', __DIR__ . '/app');
define('CORE_PATH', APP_PATH . '/core');
define('VIEWS_PATH', APP_PATH . '/views');
define('ASSETS_URL', '/assets');

// Imposta l'include path
set_include_path(get_include_path() . PATH_SEPARATOR . CORE_PATH);

// Carica l'autoloader
require_once CORE_PATH . '/autoload.php';

// Avvia l'applicazione
$app = new DAIMON\Core\App();
$app->run();