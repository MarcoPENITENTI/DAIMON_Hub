<?php
/**
 * Simple PSR-4 Autoloader
 */
spl_autoload_register(function ($class) {
    // Base directory for the namespace prefixes
    $base_dir = __DIR__ . '/../';
    
    // Mappa dei namespace PSR-4
    $prefixes = [
        'DAIMON\\' => 'src/'
    ];
    
    // Cerca il prefisso del namespace
    foreach ($prefixes as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        
        // Get the relative class name
        $relative_class = substr($class, $len);
        
        // Sostituisci il namespace con il percorso della directory
        $file = $base_dir . $dir . str_replace('\\', '/', $relative_class) . '.php';
        
        // Se il file esiste, fallo includere
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Helper function to load view files
function view($name, $data = []) {
    extract($data);
    $file = __DIR__ . "/../views/{$name}.php";
    if (file_exists($file)) {
        require $file;
    } else {
        throw new Exception("View {$name} not found");
    }
}

// Helper function to get asset URL
function asset($path) {
    return '/assets/' . ltrim($path, '/');
}
