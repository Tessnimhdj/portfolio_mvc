<?php

namespace app\Controllers;

class HomeController
{
    public function index()
    {
        include __DIR__ . "/../Views/home.php";
    }
}
