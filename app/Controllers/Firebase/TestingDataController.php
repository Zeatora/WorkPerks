<?php

namespace App\Controllers\Firebase;

use App\Controllers\BaseController;
use App\Services\FirebaseService;
use CodeIgniter\RESTful\ResourceController;

class TestingDataController extends ResourceController
{

    protected $firebase;

    public function __construct()
    {
        $this->firebase = new FirebaseService();
    }

    public function index()
    {
        $firebase = $this->firebase;
        $firestore = $firebase->getFirestore();

        // Correct Firestore reference
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

    public function addData()
    {
        $firebase = $this->firebase;
        $firestore = $firebase->getFirestore();

        $newData = [
            'name' => "Zeatora",
            'email' => "Zeatora@gmail.com"
        ];

        $firestore->database()->collection('users')->add($newData);

        return $this->respond(['status' => 'success', 'message' => 'Data added']);
    }
}
