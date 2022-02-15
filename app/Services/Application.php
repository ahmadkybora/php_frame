<?php

namespace App\Services;

use App\Services\Db\Database;
use App\Services\Db\DbModel;
use App\Models\User;
use Exception;

class Application {
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';

    protected array $eventListeners = [];

    // php 7.4
    // فیچر تایپ پراپرتی در ورژن اضافه شده است

    public static string $ROOT_DIR;

    public string $layout = 'main';
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?UserModel $user;
    public View $view;
    public static Application $app;
    // علامت سوال زیر برای این است که میتواند نداشته باشد
    public ?Controller $controller = null;

    public function __construct($rootPath, array $config)
    {
        $this->userClass = $config['userClass'];
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
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View(); 

        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get('user');
        if($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
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
    {
        $this->controller = $controller;
    }

    public function run()
    {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            echo $this->router->resolve();
        }catch(Exception $e){
            // در صورتی که کد مورد نظر 404 باشد
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }

    public function on($eventName, $callback)
    {
        $this->eventListeners[$eventName][] = $callback;
    }

    public function triggerEvent($eventName)
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach($callbacks as $callback) {
            call_user_func($callback);
        }
    }
}