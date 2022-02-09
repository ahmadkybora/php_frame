<?php

namespace App\Services;

class Application {

    // php 7.4
    // فیچر تایپ پراپرتی در ورژن اضافه شده است

    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;
    public Controller $controller;

    public function __construct($rootPath)
    {
        // مقدار زیر برای نوشتن یک بار از ثابت
        // __DIR__
        // است
        self::$ROOT_DIR = $rootPath;
        // در روش زیر 
        // $this
        // را داخل
        // self::$app
        // ریختیم تا بدین روش از 
        // $this
        // هم استفاده شود
        // example
        // Application::$app->
        self::$app = $this; 
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    // در ورزن هفت و چهار 
    // به بعد در پی اچ پی
    // میتوان برای پراپرتی ها و متد ها نوع
    // اده ای برای خروجی و ورودی آن تعیین کرد
    public function getController(): Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void
    {}

    public function run()
    {
        echo $this->router->resolve();
    }
}