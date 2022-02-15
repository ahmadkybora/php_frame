<?php
namespace App\Controllers;

use App\Models\LoginForm;
use App\Models\User;
use App\Services\Application;
use App\Services\Controller;
use App\Services\Middlewares\AuthMiddleware;
use App\Services\Request;
use App\Services\Response;

class AuthController extends Controller {

    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }

    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        if($request->isPost()) {
            $loginForm->loadData($request->getBody());
            if($loginForm->validate() && $loginForm->login()) {
                $response->redirect('/');
                return;
            }
        }
        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }

    public function register(Request $request)
    {
        $user = new User();
        if($request->isPost()) {
            // به روش زیر هم میتوان داد
            $user->loadData($request->getBody());
            
            if($user->validate() && $user->save())
            {
                Application::$app->session->setFlash('success', 'Thank for registring');
                Application::$app->response->redirect('/');
            }

            return $this->render('register', [
                'model' => $user
            ]);
        }

        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $response->redirect('/');
    }

    public function profile()
    {
        return $this->render('profile');
    }
}