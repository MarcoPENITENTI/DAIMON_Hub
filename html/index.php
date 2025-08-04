<?php
// Configurazione di base
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Definisci le costanti di base
define("BASE_PATH", dirname(__DIR__));
define("APP_PATH", BASE_PATH . "/src");
define("VIEWS_PATH", APP_PATH . "/views");
define("ASSETS_URL", "/assets");

// Carica le dipendenze
require_once APP_PATH . "/core/autoload.php";

// Avvia l'applicazione
$app = new Core\App();
$app->run();
