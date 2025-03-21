<?php

namespace Config;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Database;

class Firebase
{
    public function initialize()
    {
        $firebase = (new Factory)
            ->withServiceAccount(APPPATH . 'Config/firebase_credentials.json')
            ->withDatabaseUri('https://workperks-f6133-default-rtdb.asia-southeast1.firebasedatabase.app/');
        return $firebase;
    }

    public function getAuth(): Auth
    {
        return $this->initialize()->createAuth();
    }

    public function getDatabase(): Database
    {
        return $this->initialize()->createDatabase();
    }

    public function getFirestore()
    {
        return $this->initialize()->createFirestore();
    }
}
