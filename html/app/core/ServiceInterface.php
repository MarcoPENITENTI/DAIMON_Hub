<?php
namespace DAIMON\Core;

interface ServiceInterface {
    /**
     * Get the service name (used for routing and display)
     */
    public function getName(): string;

    /**
     * Get the service description
     */
    public function getDescription(): string;

    /**
     * Get the service version
     */
    public function getVersion(): string;

    /**
     * Get the service routes
     * Format: ['route' => ['method' => 'handler']]
     */
    public function getRoutes(): array;

    /**
     * Get the service menu items
     * Format: [['title' => 'Dashboard', 'route' => 'service.dashboard']]
     */
    public function getMenuItems(): array;

    /**
     * Check if the service is enabled
     */
    public function isEnabled(): bool;

    /**
     * Service initialization
     */
    public function initialize(): void;

    /**
     * Handle a request to this service
     */
    public function handleRequest(string $route, array $params = []);
}
