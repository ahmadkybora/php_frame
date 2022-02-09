<?php
namespace App\Controllers;

use App\Models\RegisterModel;
use App\Services\Controller;
use App\Services\Request;

class AuthController extends Controller {
    public function login()
    {
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request)
    {
        $registerModel = new RegisterModel();
        if($request->isPost()) {
            // به روش زیر هم میتوان داد
            $registerModel->loadData($request->getBody());
            
            if($registerModel->validate() && $registerModel->register())
            {
                return 'success';
            }
            dd($registerModel->errors);
            return $this->render('register', [
                'model' => $registerModel
            ]);
        }

        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ]);
    }
}