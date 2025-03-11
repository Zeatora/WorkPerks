<?php

namespace App\Controllers;

class PagesController extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Home'
        ];
        return view('pages/home', $data);
    }

    public function loginPage(): string
    {
        $data = [
            'title' => 'Login'
        ];
        return view('pages/login', $data);
    }
}
