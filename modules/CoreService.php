<?php
namespace Modules;

use Core\AbstractService;

class CoreService extends AbstractService {
    protected $name = 'Core';
    protected $description = 'Core functionality for DAIMON Hub';
    
    public function initialize(): void {
        // Register core routes
        $this->addRoute('/', 'handleDashboard', 'GET');
        $this->addRoute('/dashboard', 'handleDashboard', 'GET');
        $this->addRoute('/login', 'handleLogin', 'GET');
        $this->addRoute('/login', 'handleLoginPost', 'POST');
        $this->addRoute('/logout', 'handleLogout', 'POST');
        
        // Register menu items
        $this->addMenuItem('Dashboard', 'core.dashboard', 'home');
        $this->addMenuItem('Impostazioni', 'core.settings', 'cog', [
            ['title' => 'Profilo', 'route' => 'core.profile'],
            ['title' => 'Sicurezza', 'route' => 'core.security'],
            ['title' => 'Servizi', 'route' => 'core.services']
        ]);
    }
    
    public function handleDashboard(array $params = []) {
        return $this->app->getTemplateEngine()->render('dashboard.html', [
            'page_title' => 'Dashboard',
            'services' => $this->app->getServiceManager()->getEnabledServices(),
            'menu_items' => $this->app->getServiceManager()->getMenuItems()
        ]);
    }
    
    public function handleLogin(array $params = []) {
        return $this->app->getTemplateEngine()->render('auth/login.html', [
            'page_title' => 'Accedi'
        ]);
    }
    
    public function handleLoginPost(array $params = []) {
        // Handle login logic
        // TODO: Implement authentication
        
        // Redirect to dashboard on success
        header('Location: /dashboard');
        exit;
    }
    
    public function handleLogout(array $params = []) {
        // Handle logout logic
        // TODO: Implement logout
        
        // Redirect to login
        header('Location: /login');
        exit;
    }
}
