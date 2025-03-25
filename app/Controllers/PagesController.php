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

    public function loginPage()
    {
        $data = [
            'title' => 'Login'
        ];

        $session = session();
        $isLoggedIn = $session->get('DataUser.login');

        if ($isLoggedIn) {
            $data = [
                'title' => 'Home'
            ];
            return redirect()->to('/home')->with('status', 'warning')->with('message', 'You are already logged in')->with('data', $data);
        }

        return view('pages/login', $data);
    }

    public function signupPage(): string
    {
        $data = [
            'title' => 'Sign Up'
        ];
        return view('pages/register', $data);
    }

    public function contact(): string
    {
        $data = [
            'title' => 'Contact'
        ];
        return view('pages/contact', $data);
    }
}
