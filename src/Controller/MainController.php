<?php

namespace App\Controller;

class MainController
{
    protected $controller_name;
    public $content;
    protected $data = [];

    protected $model;

    public function __construct()
    {
        $this->controller_name = str_replace("App\\Controller\\", "", get_class($this));
        if ($this->controller_name != "LoginController")
        {
            $this->require_login();
            $this->load_model();
        }
    }

    protected function require_login()
    {
        if (!isset($_SESSION['user']))
        {
            header('Location: /login');
            exit;
        }
    }

    protected function set(array $data)
    {
        $this->data = array_merge($this->data, $data);;
    }

    public function render()
    {
        $view = debug_backtrace()[1]['function'];
        $viewFile = dirname(__DIR__, 1) . "/View/{$this->controller_name}/{$view}.php";

        if (file_exists($viewFile)) {

            ob_start();
            extract($this->data); // variables accessibles dans la vue
            require $viewFile;
            $this->content = ob_get_clean();
            require dirname(__DIR__, 1) . '/View/Layouts/template.php';
        } else {
            http_response_code(404);
            echo "Vue $viewFile introuvable";
        }
    }

    public function load_model()
    {
        $model_name = str_replace("Controller", "", $this->controller_name) . "Model";
        if (file_exists(dirname(__DIR__, 1) . '/Model/' . $model_name . '.php'))
        {
            require(dirname(__DIR__, 1) . '/Model/' . $model_name . '.php');
        }
        $this->model = new $model_name(PDO);
    }
}
