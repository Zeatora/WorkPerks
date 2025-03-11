<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Database;
use Kreait\Firebase\Storage;

class FirebaseService
{
    protected $firebase;
    protected $auth;
    protected $database;
    protected $storage;

    public function __construct()
    {
        $serviceAccountPath = ROOTPATH . 'firebase_credentials.json'; 

        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccountPath);

        $this->auth = $this->firebase->createAuth();
        $this->database = $this->firebase->createDatabase();
        $this->storage = $this->firebase->createStorage();
    }

    public function getAuth(): Auth
    {
        return $this->auth;
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }
}
