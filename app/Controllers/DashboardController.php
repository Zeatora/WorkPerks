<?php 

namespace App\Controllers;

use App\Models\usersModel;

class DashboardController extends BaseController {

    protected $usersModel;

    public function __construct() {
        $this->usersModel = new usersModel();
    }

    public function index(): string {

        $users = $this->usersModel->findAll();
        
        $data = [
            'title' => 'Dashboard',
            'users' => $users
        ];

        return view('authentication/dashboard', $data);
    }

    public function createPage(): string {
        $data = [
            'title' => 'Create User'
        ];

        return view('pages/create', $data);
    }

    public function createFunction() {
        $this->usersModel->save([
            'email' => $this->request->getVar('email'),
            'name' => $this->request->getVar('name'),
            'username' => $this->request->getVar('username')
        ]);

        return redirect()->to('/pages/dashboard');
    }

    public function delete($id) {
        $this->usersModel->delete($id);
        return redirect()->to('/pages/dashboard');
    }

    public function modify($id) {
        $data = [
            'title' => 'Modify User',
            'user' => $this->usersModel->find($id)
        ];

        return view('pages/edit', $data);
    }

    public function update($id) {

        $this->usersModel->save([
            'id' => $id,
            'email' => $this->request->getVar('email'),
            'name' => $this->request->getVar('name')
        ]);

        return redirect()->to('/pages/dashboard');
    }
}