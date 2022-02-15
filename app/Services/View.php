<?php

namespace App\Services;

class View
{
    public string $title = '';

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
        $layout = Application::$app->layout;
        if(Application::$app->controller) {
            $layout = Application::$app->controller->layout;
        }
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