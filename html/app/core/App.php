<?php
namespace DAIMON\Core;

class App {
    private $templateEngine;
    
    public function __construct() {
        // Initialize template engine
        $this->templateEngine = new TemplateEngine();
        
        // Set up error handling
        $this->setupErrorHandling();
    }

    public function run() {
        try {
            // Get clean path segments
            $path = $this->getCleanPath();
            
            if (getenv('APP_ENV') !== 'production') {
                error_log('Routing path: ' . $path);
            }
            
            // Define routes with their handlers
            $routes = [
                '/' => function() {
                    $this->render('home.php', [
                        'page_title' => 'DAIMON Hub - Dashboard',
                        'welcome_message' => 'Benvenuto in DAIMON Hub'
                    ]);
                },
                // Add more routes here
            ];
            
            // Execute route handler or show 404
            if (isset($routes[$path])) {
                $routes[$path]();
            } else {
                if (getenv('APP_ENV') !== 'production') {
                    error_log('Route not found: ' . $path);
                }
                $this->handle404();
            }
            
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }
    
    private function render($template, $data = []) {
        echo $this->templateEngine->render($template, $data);
    }
    
    private function handle404() {
        header('HTTP/1.0 404 Not Found');
        $this->render('errors/404.php', [
            'page_title' => 'Pagina non trovata'
        ]);
    }
    
    public function handleError(\Throwable $e) {
        error_log($e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
        
        if (getenv('APP_ENV') !== 'production') {
            echo '<h1>Errore</h1>';
            echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        } else {
            $this->render('errors/500.html', [
                'page_title' => 'Errore del server'
            ]);
        }
    }
    
    private function getCleanPath() {
        // Ottieni l'URI dalla richiesta
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Gestisci il caso speciale di URL che iniziano con //
        if (strpos($requestUri, '//') === 0) {
            // Rimuovi il doppio slash iniziale e normalizza
            $path = '/' . ltrim($requestUri, '/');
        } else {
            // Estrai il path dall'URI
            $path = parse_url($requestUri, PHP_URL_PATH) ?? '/';
        }
        
        // Debug in ambiente di sviluppo
        if (getenv('APP_ENV') !== 'production') {
            error_log('Original URI: ' . $requestUri);
            error_log('Initial path: ' . $path);
        }
        
        // 1. Normalizza gli slash multipli
        $normalized = preg_replace('#/+#', '/', $path);
        
        // 2. Gestisci il caso di path vuoto
        if ($normalized === '') {
            $normalized = '/';
        }
        
        // 3. Assicurati che inizi con uno slash
        if (!str_starts_with($normalized, '/')) {
            $normalized = '/' . $normalized;
        }
        
        // 4. Validazione di sicurezza
        if (str_contains($normalized, '/../') || str_contains($normalized, '/./')) {
            if (getenv('APP_ENV') !== 'production') {
                error_log('Security alert: Potential directory traversal attempt detected: ' . $normalized);
            }
            return '/404';
        }
        
        // 5. Prepara l'URI normalizzato per il reindirizzamento
        $normalizedUri = $normalized;
        if (($query = parse_url($requestUri, PHP_URL_QUERY)) !== null) {
            $normalizedUri .= '?' . $query;
        }
        
        // 6. Log del percorso normalizzato
        if (getenv('APP_ENV') !== 'production') {
            error_log('Normalized path: ' . $normalized);
        }
        
        // 7. Reindirizza se necessario (e non è una richiesta 404)
        if ($requestUri !== $normalizedUri && $normalized !== '/404') {
            header('Location: ' . $normalizedUri, true, 301);
            exit;
        }
        
        return $normalized;
    }
    
    private function setupErrorHandling() {
        error_reporting(E_ALL);
        ini_set('display_errors', getenv('APP_ENV') !== 'production' ? '1' : '0');
        
        set_exception_handler([$this, 'handleError']);
        
        // Set timezone
        date_default_timezone_set('Europe/Rome');
    }
}
