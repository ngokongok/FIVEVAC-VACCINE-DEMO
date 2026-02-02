<?php
// Mark that the application context is being executed through the front controller
define('IN_APP', true);

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Helpers.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/CSRF.php';

spl_autoload_register(function($class){
  $paths = [__DIR__ . '/../app/controllers/' . $class . '.php',
            __DIR__ . '/../app/core/' . $class . '.php',
            __DIR__ . '/../app/models/' . $class . '.php'];
  foreach($paths as $p) if (file_exists($p)) { require_once $p; return; }
});

if (!class_exists('Controller')){
  class Controller {
    protected function view($path, $data = []){
      extract($data);
      require __DIR__ . '/../app/views/layout/header.php';
      require __DIR__ . '/../app/views/' . $path . '.php';
      require __DIR__ . '/../app/views/layout/footer.php';
    }
  }
}

$path = trim($_GET['url'] ?? '', '/'); // e.g. auth/register
$parts = array_values(array_filter(explode('/', $path)));
$controllerName = !empty($parts) ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
$method = $parts[1] ?? 'index';
$params = array_slice($parts, 2);

if (!class_exists($controllerName)) $controllerName = 'HomeController';
if (!class_exists($controllerName)) {
  class HomeController extends Controller {
    public function index(){ $this->view('home/home', []); }
  }
}
$controller = new $controllerName();
if (!method_exists($controller, $method)) { $method = 'index'; }
call_user_func_array([$controller, $method], $params);