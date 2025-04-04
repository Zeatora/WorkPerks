<?php
namespace App\Controllers\Firebase;
use App\Services\FirebaseService;
use CodeIgniter\RESTful\ResourceController;

class FirebaseController extends ResourceController
{
    protected $format = 'json';
    protected $firebaseService;

    public function __construct()
    {
        $this->firebaseService = new FirebaseService();
    }

    public function index()
    {
        return $this->respond([
            'status' => 200,
            'message' => 'Firebase Controller'
        ]);
    }

    public function auth()
    {
        $auth = $this->firebaseService->getAuth();
        $user = $auth->getUser('some-uid');

        return $this->respond([
            'status' => 200,
            'message' => 'Firebase Auth',
            'data' => $user
        ]);
    }


    public function storage()
    {
        $storage = $this->firebaseService->getStorage();
        $bucket = $storage->getBucket('some-bucket');
        $object = $bucket->object('some-object');
        $object->downloadToFile('some-file');

        return $this->respond([
            'status' => 200,
            'message' => 'Firebase Storage'
        ]);
    }
}