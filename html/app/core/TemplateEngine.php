<?php
namespace DAIMON\Core;

class TemplateEngine {
    private $templateDir;
    private $globals = [];
    
    public function __construct() {
        $this->templateDir = __DIR__ . '/../views/';
        $this->globals['base_url'] = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    
    /**
     * Render a template with the given data
     */
    public function render($template, array $data = []) {
        // Merge global variables with template data
        $data = array_merge($this->globals, $data);
        
        // Extract variables to local scope
        extract($data, EXTR_SKIP);
        
        // Start output buffering
        ob_start();
        
        // Include the template file
        $templatePath = $this->templateDir . ltrim($template, '/');
        
        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template not found: {$template}");
        }
        
        try {
            include $templatePath;
            return ob_get_clean();
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }
    
    /**
     * Add a global variable available in all templates
     */
    public function addGlobal($key, $value) {
        $this->globals[$key] = $value;
    }
    
    /**
     * Escape a string for safe output
     */
    public function escape($value) {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
    
    /**
     * Include a partial template
     */
    public function include($template, array $data = []) {
        return $this->render($template, $data);
    }
}
