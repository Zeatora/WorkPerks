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
            ->withDatabaseUri('https://workperks-338ce.firebaseio.com');
        return $firebase;
    }

    public function getAuth(): Auth
    {
        return $this->initialize()->createAuth();
    }

    public function getFirestore()
    {
        return $this->initialize()->createFirestore();
    }
}
