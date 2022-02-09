<?php
namespace App\Services;

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
            // در صورتی که کد مورد نظر 404 باشد
            $this->response->setStatusCode(404);
            return $this->renderView("_404");
        }

        // در صورتی که رسته باشد
        if(is_string($callback)) {
            return $this->renderView($callback);
        }

        // در صورتی که آرایه باشد 
        // یک آبجکت جدید میسازد
        // در اندیس صفرم
        if(is_array($callback)) {
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;
        }

        return call_user_func($callback, $this->request);
    }

    // 3 مند زیر برای 
    // ساخت یک نوع تمپلیت انجین ساده است
    // که اگر به کلمه مورد نظر برسد
    // مقادیر فایل مورد نظر را در فایل دگر جایگزینی میکند
    public function renderView($view, $params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);

        // اگر در مقدار مورد نظر که دو فایل
        // میباشد برسد مقادریر را جایگزین میکند
        return str_replace('{{content}}', $viewContent, $layoutContent);

        include_once Application::$ROOT_DIR . '/views/' . $view . '.php';
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();

        // اگر در مقدار مورد نظر که دو فایل
        // میباشد برسد مقادریر را جایگزین میکند
        return str_replace('{{content}}', $viewContent, $layoutContent);

        include_once Application::$ROOT_DIR . '/views/' . $view . '.php';
    }

    protected function layoutContent()
    {
        $layout = Application::$app->controller->layout;
        ob_start();
        include_once Application::$ROOT_DIR . '/views/layouts/' . $layout . '.php';
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {
        foreach($params as $key => $value) {
            // https://www.php.net/manual/en/language.variables.variable.php
            // varaible varailbles
            // اسم متغییر را برمیگرداند 
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . '/views/' . $view . '.php';
        return ob_get_clean();
    }
}

// echo '<pre style="background-color:orange; width: 250px">';
// var_dump($params);
// echo '</pre>';
// exit;