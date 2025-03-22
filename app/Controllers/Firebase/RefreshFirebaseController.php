<?php 

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Google\Client;

class RefreshFirebaseToken extends BaseCommand
{
    protected $group = 'firebase';
    protected $name = 'firebase:refresh-token';
    protected $description = 'Refresh Firebase authentication token';

    public function run(array $params)
    {
        $serviceAccountPath = realpath(__DIR__ . '/../../app/Config/firebase_credentials.json');

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $serviceAccountPath);

        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope('https://www.googleapis.com/auth/cloud-platform');

        $accessToken = $client->fetchAccessTokenWithAssertion();
        $token = $accessToken['access_token'] ?? null;

        if ($token) {
            CLI::write("Firebase token refreshed successfully!", 'green');
        } else {
            CLI::write("Failed to refresh Firebase token!", 'red');
        }
    }
}
