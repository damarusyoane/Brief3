<?php
namespace App\Core;

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        include_once "../app/Views/$view.php";
    }

    public function model($model)
    {
        $modelPath = "../app/Models/$model.php";
        if (file_exists($modelPath)) {
            include_once $modelPath;
            return new $model();
        }
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit();
    }
}