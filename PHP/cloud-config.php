<?php
require_once 'cloud-db.php';
  
define('GOOGLE_CLIENT_ID', '454685669116-9ta10ceuqsapjk50qmmmsrs394kbh7qb.apps.googleusercontent.com');
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

