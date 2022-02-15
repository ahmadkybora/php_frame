<?php
namespace App\Services;

use App\Services\Exception\ForbiddenException;
use App\Services\Exception\NotFoundException;

class Router {

    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    // متد زیر برای درخواست های 
    // get
    // میباشد
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    // متد زیر برای درخواست های 
    // post
    // میباشد
    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if($callback === false) {
            throw new NotFoundException();
        }

        // در صورتی که رسته باشد
        if(is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        // در صورتی که آرایه باشد 
        // یک آبجکت جدید میسازد
        // در اندیس صفرم
        if(is_array($callback)) {
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;
            foreach($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }

        return call_user_func($callback, $this->request, $this->response);
    }

    // 3 مند زیر برای 
    // ساخت یک نوع تمپلیت انجین ساده است
    // که اگر به کلمه مورد نظر برسد
    // مقادیر فایل مورد نظر را در فایل دگر جایگزینی میکند
    // public function renderView($view, $params = [])
    // {
    //     return Application::$app->view->renderView($view, $params);
    // }

    // public function renderContent($viewContent)
    // {
    //     return Application::$app->view->renderView($view, $params);
    // }

    // protected function layoutContent()
    // {
    //     $layout = Application::$app->layout;
    //     if(Application::$app->controller) {
    //         $layout = Application::$app->controller->layout;
    //     }
    //     ob_start();
    //     include_once Application::$ROOT_DIR . '/views/layouts/' . $layout . '.php';
    //     return ob_get_clean();
    // }

    // protected function renderOnlyView($view, $params)
    // {
    //     foreach($params as $key => $value) {
    //         // https://www.php.net/manual/en/language.variables.variable.php
    //         // varaible varailbles
    //         // اسم متغییر را برمیگرداند 
    //         $$key = $value;
    //     }

    //     ob_start();
    //     include_once Application::$ROOT_DIR . '/views/' . $view . '.php';
    //     return ob_get_clean();
    // }
}

// echo '<pre style="background-color:orange; width: 250px">';
// var_dump($params);
// echo '</pre>';
// exit;