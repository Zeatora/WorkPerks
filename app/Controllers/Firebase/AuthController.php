<?php

namespace App\Controllers\Firebase;

use Config\Firebase;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    public function registerUser()
    {
        $firebase = new Firebase();
        $auth = $firebase->getAuth();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        try {
            $user = $auth->createUserWithEmailAndPassword($email, $password);
            return $this->respond(['status' => 'success', 'user' => $user->uid]);
        } catch (\Exception $e) {
            return $this->respond(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }
}
