<?php
require_once 'config.php';
 
$drive_folder_id = '1xeq5etB0YeGMiLI_RUYVeWFWW1QI73u4';
create_file_in_drive_folder($drive_folder_id);
  
function create_file_in_drive_folder($drive_folder_id) {
  
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
        $file = getcwd(). '/1.jpg';
        $filename = basename($file);
        $filetype = mime_content_type($file);
 
        $resource = new Google\Service\Drive\DriveFile([
            'name' => $filename,
            'parents' => [$drive_folder_id],
        ]);
 
        $result = $service->files->create($resource, [
            'data' => file_get_contents($file),
            'mimeType' => $filetype,
            'uploadType' => 'multipart',
        ]);
        echo "File is uploaded successfully.";
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
  
            create_file_in_drive_folder($drive_folder_id);
        } else {
            echo $e->getMessage(); //print the error
        }
    }
}