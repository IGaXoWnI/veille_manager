<?php
class Router {
    private $routes = [
        'GET|/' => ['HomeController', 'index'],
        'GET|/login' => ['AuthController', 'showLogin'],
        'POST|/login' => ['AuthController', 'login'],
        'GET|/register' => ['AuthController', 'showRegister'],
        'POST|/register' => ['AuthController', 'register'],
        'GET|/logout' => ['AuthController', 'logout'],
        'GET|/calendar' => ['CalendarController', 'index'],
        
        // Dashboard routes
        'GET|/dashboard' => ['DashboardController', 'index'],
        'GET|/dashboard/teacher' => ['DashboardController', 'teacherDashboard'],
        'GET|/dashboard/student' => ['DashboardController', 'studentDashboard'],
        
        // Subject routes
        'GET|/subjects/suggest' => ['SubjectController', 'showSuggestForm'],
        'POST|/subjects/suggest' => ['SubjectController', 'suggest'],
        'GET|/subjects/manage' => ['SubjectController', 'manage'],
        'POST|/subjects/approve' => ['SubjectController', 'approve'],
        'POST|/subjects/reject' => ['SubjectController', 'reject'],
        
        // Presentation routes
        'GET|/presentations/schedule' => ['PresentationController', 'showScheduleForm'],
        'POST|/presentations/schedule' => ['PresentationController', 'schedule'],
        'GET|/presentations/edit' => ['PresentationController', 'showEditForm'],
        'POST|/presentations/update' => ['PresentationController', 'updateSchedule'],
        'POST|/presentations/delete' => ['PresentationController', 'deleteSchedule'],
        
        // User management routes
        'GET|/user-management' => ['UserManagementController', 'index'],
        'POST|/user-management/approve' => ['UserManagementController', 'approveUser'],
        'POST|/user-management/delete' => ['UserManagementController', 'deleteUser'],
    ];

    private $publicRoutes = [
        '/calendar',
        '/login',
        '/register',
        '/'
    ];

    public function handleRequest() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace(BASE_URL, '', $uri); 
        $uri = trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];
        
        if (empty($uri)) {
            $uri = '/';
        } else {
            $uri = '/' . $uri;
        }
        
        $route = "$method|$uri";

       

        if (!in_array($uri, $this->publicRoutes) && !isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if (array_key_exists($route, $this->routes)) {
            [$controller, $method] = $this->routes[$route];
            $controllerClass = new $controller();
            $controllerClass->$method();
        } else {
            header("HTTP/1.0 404 Not Found");
            require APP_PATH . '/app/views/errors/404.php';
        }
    }
}