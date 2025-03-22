<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Firestore;
use Kreait\Firebase\Storage;
use Google\Auth\CredentialsLoader;
use Google\Auth\ApplicationDefaultCredentials;

class FirebaseService
{
    protected $firebase;
    protected $auth;
    protected $firestore;
    protected $storage;

    public function __construct()
    {
        $serviceAccountPath = realpath(ROOTPATH . 'writable/firebase_credentials.json');

        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccountPath)
            ->withDatabaseUri('https://workperks-338ce.firebaseio.com');

        $this->auth = $this->firebase->createAuth();
        $this->firestore = $this->firebase->createFirestore();
        $this->storage = $this->firebase->createStorage();
    }

    public function getAuth(): Auth
    {
        return $this->auth;
    }

    public function getFirestore(): Firestore
    {
        return $this->firestore;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }
}
