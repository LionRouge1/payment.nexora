<?php
namespace App\Controllers;

class ApplicationController
{
    // protected $db;

    // public function __construct()
    // {
    //     $this->db = \App\Config\Database::getInstance();
    // }

    public function render($view, $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new \Exception("View file {$viewPath} not found");
        }

        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/main.php';
    }

    public function notFound()
    {
        http_response_code(404);
        $this->render('errors/404');
        exit();
    }
}