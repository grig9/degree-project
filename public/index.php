<?php
// Start a Session
if( !session_id() ) @session_start();

require '../vendor/autoload.php';
use League\Plates\Engine;
use Delight\Auth\Auth;
use Illuminate\Contracts\Pagination\Paginator;
use Tamtamchik\SimpleFlash\Flash;

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
  Engine::class => function() {
    return new Engine('../app/views');
  },

  PDO::class => function() {
      $database_name = "app";
      $username = "root";
      $password = "";
      $connection = "mysql:host=localhost";
      $charset = 'utf8';

    return new PDO(
      "$connection;dbname=$database_name;charset=$charset", 
      $username, 
      $password
    );
  },

  Auth::class => function($container) {
    return new Auth($container->get('PDO'));
  },

  Flash::class => function() {
    return new Flash();
  },

]);

$container = $builder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

  $r->addRoute('GET', '/', ['App\controllers\LoginController', 'login_form']);
  $r->addRoute('POST', '/login', ['App\controllers\LoginController', 'login']);

  $r->addRoute('POST', '/registration', ['App\controllers\RegistrationController', 'registration']);

  $r->addRoute('GET', '/registration-form', ['App\controllers\RegistrationController', 'registration_form']);

  $r->addRoute('GET', '/users/{id:\d+}', ['App\controllers\HomeController', 'users']);

  $r->addRoute('GET', '/logout', ['App\controllers\HomeController', 'logout']);

  $r->addRoute('GET', '/status-user/{id:\d+}', ['App\controllers\HomeController', 'status_form']);
  $r->addRoute('POST', '/set-user-status', ['App\controllers\HomeController', 'set_user_status']);

  $r->addRoute('GET', '/page-profile/{id:\d+}', ['App\controllers\HomeController', 'page_profile']);

  $r->addRoute('GET', '/media-form/{id:\d+}', ['App\controllers\MediaController', 'media_form']);
  $r->addRoute('POST', '/image', ['App\controllers\MediaController', 'download_image']);

  
  $r->addRoute('GET', '/create-user-form', ['App\controllers\HomeController', 'create_user_form']);
  $r->addRoute('POST', '/create-user', ['App\controllers\HomeController', 'create_user']);

  $r->addRoute('GET', '/edit-user-form/{id:\d+}', ['App\controllers\HomeController', 'edit_user_form']);
  $r->addRoute('POST', '/edit-user', ['App\controllers\HomeController', 'edit_user']);

  $r->addRoute('GET', '/verification', ['App\controllers\HomeController', 'verification']);

  // {id} must be a number (\d+)
  $r->addRoute('GET', '/user-delete/{id:\d+}', ['App\controllers\HomeController', 'user_delete']);

  $r->addRoute('GET', '/security-user/{id:\d+}', ['App\controllers\HomeController', 'security_form']);
  $r->addRoute('POST', '/security', ['App\controllers\HomeController', 'security']);
    
  // The /{title} suffix is optional
  // $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
  $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
  case FastRoute\Dispatcher::NOT_FOUND:
      // ... 404 Not Found
      echo '404';
      break;
  case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
      $allowedMethods = $routeInfo[1];
      // ... 405 Method Not Allowed
      echo 'Method not allowed';
      break;
  case FastRoute\Dispatcher::FOUND:
      $handler = $routeInfo[1];
      $vars = $routeInfo[2];
      
      
      $container->call($handler, $vars);
      break;
}