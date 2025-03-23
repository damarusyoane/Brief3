<?php
namespace App\Core;

class View
{
    public static function render($view, $data = [])
    {
        extract($data);
        include_once "../app/Views/$view.php";
    }
}
