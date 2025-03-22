<?php

namespace App\Controllers\Firebase;

use App\Controllers\BaseController;
use App\Services\FirebaseService;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends BaseController
{
    protected $firebase;

    public function __construct()
    {
        $this->firebase = new FirebaseService();
    }
    public function registerUser()
    {
        $auth = $this->firebase->getAuth();
        $firestore = $this->firebase->getFirestore();
        $users = $firestore->database()->collection('users');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'username' => 'required|alpha_numeric',
            'name' => 'required|alpha_space',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/register')->withInput()->with('status', 'errors')->with('message', $validation->getErrors(), 400);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $username = $this->request->getPost('username');
        $name = $this->request->getPost('name');

        $newData = [
            'email' => $email,
            'username' => $username,
            'name' => $name,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $user = $auth->createUserWithEmailAndPassword($email, $password);
            $newUser = $users->document($user->uid)->set($newData);
            
            // Set flashdata for success message
            
            return redirect()->to('/login')->with('status', 'success')->with('message', 'Account created successfully');
        } catch (\Exception $e) {
            return redirect()->to('/register')->withInput()->with('status', 'errors')->with('message', $e->getMessage(), 400);
        }
    }

    public function loginUser() {
        $firebase = $this->firebase;
        $auth = $firebase->getAuth();
        $firestore = $firebase->getFirestore();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        try {
            $user = $auth->signInWithEmailAndPassword($email, $password);
            $users = $firestore->database()->collection('users');
            $userDoc = $users->document($user->firebaseUserId())->snapshot();
        
            if (!$userDoc->exists()) {
                return redirect()->to('/login')->with('status', 'error')->with('message', 'User data not found in Firestore');
            }
        
            $Data = array(
                'Data' => $userDoc->data(),
                'login' => true
            );
        
            session()->set('DataUser', $Data);
        
            return redirect()->to('/home')->with('status', 'success')->with('message', 'Login successfully');
        } catch (\Exception $e) {
            return redirect()->to('/register')->withInput()->with('status', 'errors')->with('message', $e->getMessage(), 400);
        }
    }

    public function logoutUser() {
        $firebase = $this->firebase;
        $auth = $firebase->getAuth();

        try {
            session()->destroy();
            return redirect()->to('/login')->with('status', 'success')->with('message', 'Logout successfully');
        } catch (\Exception $e) {
            return redirect()->to('/register')->withInput()->with('status', 'errors')->with('message', $e->getMessage(), 400);
        }
    }
}
