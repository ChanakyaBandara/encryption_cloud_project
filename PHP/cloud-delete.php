<?php
require_once 'cloud-config.php';
 
delete_file_from_drive('1.jpg'); // say 1.jpg
  
function delete_file_from_drive($filename = '') {
  
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
        // Check for $filename and include the file ID and size
        $files = $service->files->listFiles([
            'q' => "name='$filename'",
            'fields' => 'files(id)'
        ]);
 
        if(count($files) == 0) {
            echo "No files found.";
            return;
        }
 
        // Determine the file's ID
        $fileId = $files[0]->id;
 
        $result = $service->files->delete($fileId);
 
        echo "File is deleted from Google Drive.";
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
  
            delete_file_from_drive($filename);
        } else {
            echo $e->getMessage(); //print the error
        }
    }
}