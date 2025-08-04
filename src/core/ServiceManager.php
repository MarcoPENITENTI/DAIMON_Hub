<?php
namespace Core;

class ServiceManager {
    private $services = [];
    private $app;
    
    public function __construct(App $app) {
        $this->app = $app;
    }
    
    /**
     * Register a service
     */
    public function registerService(string $serviceClass): void {
        if (!class_exists($serviceClass)) {
            throw new \RuntimeException("Service class not found: {$serviceClass}");
        }
        
        if (!is_subclass_of($serviceClass, ServiceInterface::class)) {
            throw new \RuntimeException("Service must implement ServiceInterface: {$serviceClass}");
        }
        
        $service = new $serviceClass($this->app);
        $this->services[$service->getName()] = $service;
    }
    
    /**
     * Get all registered services
     */
    public function getServices(): array {
        return $this->services;
    }
    
    /**
     * Get a service by name
     */
    public function getService(string $name): ?ServiceInterface {
        return $this->services[$name] ?? null;
    }
    
    /**
     * Get all enabled services
     */
    public function getEnabledServices(): array {
        return array_filter($this->services, function($service) {
            return $service->isEnabled();
        });
    }
    
    /**
     * Get all menu items from enabled services
     */
    public function getMenuItems(): array {
        $menuItems = [];
        
        foreach ($this->getEnabledServices() as $service) {
            $menuItems = array_merge($menuItems, $service->getMenuItems());
        }
        
        return $menuItems;
    }
    
    /**
     * Handle a request by checking all services for a matching route
     */
    public function handleRequest(string $path, string $method = 'GET') {
        foreach ($this->getEnabledServices() as $service) {
            foreach ($service->getRoutes() as $route => $config) {
                if ($this->matchesRoute($path, $route) && 
                    $this->matchesMethod($method, $config['method'] ?? 'GET')) {
                    
                    $params = $this->extractParams($path, $route);
                    return $service->handleRequest($route, $params);
                }
            }
        }
        
        return null;
    }
    
    private function matchesRoute(string $path, string $route): bool {
        $pattern = $this->convertRouteToRegex($route);
        return (bool) preg_match($pattern, $path);
    }
    
    private function matchesMethod(string $requestMethod, string $routeMethod): bool {
        return strtoupper($requestMethod) === strtoupper($routeMethod);
    }
    
    private function extractParams(string $path, string $route): array {
        $pattern = $this->convertRouteToRegex($route);
        preg_match($pattern, $path, $matches);
        
        // Remove numeric keys and route from matches
        return array_filter($matches, function($key) {
            return !is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);
    }
    
    private function convertRouteToRegex(string $route): string {
        // Convert route parameters to named capture groups
        $pattern = preg_replace('/\{([^\/]+)\}/', '(?P<$1>[^\/]+)', $route);
        
        // Add start and end anchors
        return '#^' . $pattern . '$#';
    }
}
