<?php
/**
 * Simple PSR-4 Autoloader
 */
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'App\\';
    
    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/../classes/';
    
    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }
    
    // Get the relative class name
    $relative_class = substr($class, $len);
    
    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators, append with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
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
