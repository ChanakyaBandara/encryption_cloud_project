<?php
require_once 'config.php';
  
create_drive_folder('apifolder');
  
function create_drive_folder($folder) {
  
    $client = new Google_Client();
  
    $db = new DB();
  
    $arr_token = (array) $db->get_access_token();
    $accessToken = array(
        'access_token' => $arr_token['access_token'],
        'expires_in' => $arr_token['expires_in'],
    );
  
    $client->setAccessToken($accessToken);
 
    $service = new Google\Service\Drive($client);
 
    try {
        // Create a folder in root
        $postBody = new Google\Service\Drive\DriveFile([
            'name' => $folder,
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);
 
        $result = $service->files->create($postBody);
        echo "Folder is created successfully.";
        print_r ($result);
    } catch(Exception $e) {
        if( 401 == $e->getCode() ) {
            $refresh_token = $db->get_refersh_token();
  
            $client = new GuzzleHttp\Client(['base_uri' => 'https://accounts.google.com']);
  
            $response = $client->request('POST', '/o/oauth2/token', [
                'form_params' => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token,
                    "client_id" => GOOGLE_CLIENT_ID,
                    "client_secret" => GOOGLE_CLIENT_SECRET,
                ],
            ]);
  
            $data = (array) json_decode($response->getBody());
            $data['refresh_token'] = $refresh_token;
  
            $db->update_access_token(json_encode($data));
  
            create_drive_folder($folder);
        } else {
            echo $e->getMessage(); //print the error
        }
    }
}