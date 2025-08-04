<?php
namespace Core;

abstract class AbstractService implements ServiceInterface {
    protected $name;
    protected $description;
    protected $version = '1.0.0';
    protected $enabled = true;
    protected $routes = [];
    protected $menuItems = [];
    protected $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->initialize();
    }

    public function getName(): string {
        return $this->name ?? get_class($this);
    }

    public function getDescription(): string {
        return $this->description ?? '';
    }

    public function getVersion(): string {
        return $this->version;
    }

    public function getRoutes(): array {
        return $this->routes;
    }

    public function getMenuItems(): array {
        return $this->menuItems;
    }

    public function isEnabled(): bool {
        return $this->enabled;
    }

    public function initialize(): void {
        // Override in child classes
    }

    public function handleRequest(string $route, array $params = []) {
        $handler = $this->routes[$route] ?? null;
        
        if (!$handler) {
            throw new \RuntimeException("Route not found: {$route}");
        }

        if (is_callable($handler)) {
            return $handler($params);
        }

        if (is_string($handler) && method_exists($this, $handler)) {
            return $this->{$handler}($params);
        }

        throw new \RuntimeException("Invalid handler for route: {$route}");
    }

    protected function addRoute(string $path, $handler, string $method = 'GET'): void {
        $this->routes[$path] = [
            'method' => strtoupper($method),
            'handler' => $handler
        ];
    }

    protected function addMenuItem(string $title, string $route, ?string $icon = null, array $children = []): void {
        $this->menuItems[] = [
            'title' => $title,
            'route' => $route,
            'icon' => $icon,
            'children' => $children
        ];
    }
}
