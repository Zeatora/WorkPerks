<?php

namespace App\Controllers\Firebase;

use App\Controllers\BaseController;
use Config\Firebase;
use CodeIgniter\RESTful\ResourceController;
use Google\Cloud\Firestore\FirestoreClient;

class TestingDataController extends ResourceController  {

    protected $firebase;

    public function __construct() {
        $this->firebase = new Firebase();
    }

    public function index() {
        $firebase = $this->firebase;
        $firestore = $firebase->getFirestore();

        $collectionRef = $firestore->database()->collection('users');
        $documents = $collectionRef->documents();
        
        $users = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $users[] = [
                    'id' => $document->id(),
                    'data' => $document->data()
                ];
            }
        }

        $data = [
            'title' => "Testing Firebase",
            'users' => $users,
        ];

        
       return view('authentication/FirebaseTesting', $data);
    }

    public function addData() {
        $firebase = $this->firebase;
        $database = $firebase->getDatabase();

        $newData = [
            'name' => "Zeatora",
            'email' => "Zeatora@gmail.com"
        ];

        $database->getReference('users')->push($newData);

        return $this->respond(['status' => 'success', 'message' => 'Data added']);
    }
}