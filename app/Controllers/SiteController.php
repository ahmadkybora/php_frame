<?php
namespace App\Controllers;

use App\Models\ContactForm;
use App\Services\Application;
use App\Services\Controller;
use App\Services\Request;
use App\Services\Response;

class SiteController extends Controller{

    public function home()
    {
        $params = [
            'name' => 'me',
            'family' => 'mms',
        ];
        return $this->render('home', $params);
    }

    public function contact(Request $request, Response $response)
    {
        $contact = new ContactForm();
        if($request->isPost()) {
            $contact->loadData($request->getBody());
            if($contact->validate() && $contact->send()) {
                Application::$app->session->setFlash('success', 'Thank you');
                return $response->redirect('/contact');
            }
        }
        return $this->render('contact', [
            'model' => $contact
        ]);
    }
}