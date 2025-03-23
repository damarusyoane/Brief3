<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home/index');
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../auth/login");
            exit();
        }

        $this->view('home/dashboard', ['username' => $_SESSION['username']]);
    }
}
