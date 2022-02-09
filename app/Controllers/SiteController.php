<?php
namespace App\Controllers;

use App\Services\Controller;
use App\Services\Request;

class SiteController extends Controller{

    public function home()
    {
        $params = [
            'name' => 'me',
            'family' => 'mms',
        ];
        return $this->render('home', $params);
    }
    public function contact()
    {
        return $this->render('contact');
    }

    public function handleContact(Request $request)
    {
        $body = $request->getBody();
        echo '<pre style="background-color:orange; width: 250px">';
        var_dump($body);
        echo '</pre>';
        exit;
    }
}