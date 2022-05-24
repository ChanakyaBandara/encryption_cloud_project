<?php
require_once 'vendor/autoload.php';
require_once 'cloud-db.php';
  
define('GOOGLE_CLIENT_ID', '1046218056680-oupb7t03a1p6j3nh0qvjqcdiina5ua7k.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
  
$config = [
    'callback' => 'http://localhost/encryption_cloud_project/PHP/callback.php',
    'keys'     => [
                    'id' => GOOGLE_CLIENT_ID,
                    'secret' => GOOGLE_CLIENT_SECRET
                ],
    'scope'    => 'https://www.googleapis.com/auth/drive',
    'authorize_url_parameters' => [
            'approval_prompt' => 'force', // to pass only when you need to acquire a new refresh token.
            'access_type' => 'offline'
    ]
];
  
$adapter = new Hybridauth\Provider\Google( $config );

