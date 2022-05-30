<?php
require_once 'cloud-config.php';
 
// download_drive_file('1.jpg'); // say 1.jpg
 
function download_drive_file($filename = '') {
 
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
            'fields' => 'files(id,size)'
        ]);
 
        if(count($files) == 0) {
            echo "No files found.";
            return;
        }
 
        // Determine the file's size and ID
        $fileId = $files[0]->id;
        $fileSize = intval($files[0]->size);
 
        // Get the authorized Guzzle HTTP client
        $http = $client->authorize();
 
        // Open a file for writing
        $fp = fopen(time().'-'.$filename, 'w');
 
        // Download in 1 MB chunks
        $chunkSizeBytes = 1 * 1024 * 1024;
        $chunkStart = 0;
 
        // Iterate over each chunk and write it to our file
        while ($chunkStart < $fileSize) {
            $chunkEnd = $chunkStart + $chunkSizeBytes;
            $response = $http->request(
                'GET',
                sprintf('/drive/v3/files/%s', $fileId),
                [
                    'query' => ['alt' => 'media'],
                    'headers' => [
                        'Range' => sprintf('bytes=%s-%s', $chunkStart, $chunkEnd)
                    ]
                ]
            );
            $chunkStart = $chunkEnd + 1;
            fwrite($fp, $response->getBody()->getContents());
        }
 
        // close the file pointer
        fclose($fp);
 
        echo "File is downloaded to your filesystem.";
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
  
            download_drive_file($filename);
        } else {
            echo $e->getMessage(); //print the error
        }
    }
}