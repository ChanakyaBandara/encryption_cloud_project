<?php
// define('SITE_ROOT',dirname(__FILE__));
if(isset($_GET['path'],$_GET['name'])){

    $path_dir = $_GET['path'];
    $file_Name = $_GET['name'];
    $fileName = basename($file_Name);
    $filePath = $path_dir.$fileName;
    //echo $path_dir;
    if(!empty($fileName) && file_exists($filePath)){
        //Define headers
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: applicatin/zip");
        header("Content-Transfer-Encoding: binary");

        //Read the file
        
        readfile($filePath);
        exit;

    }
}

if(isset($_GET['path'],$_GET['namearr'])){
        $path_dir = $_GET['path'];
        $namearr=$_GET['namearr'];
        echo $namearr;
        $zip = new ZipArchive;
        $zip_name = time().'.zip';
        foreach($namearr as $name){
                array_push($filesToZip,$name);
            }
        if($zip->open($zip_name,ZipArchive::CREATE)===TRUE){
            foreach($filesToZip as $file){
                $zip->addFile($path_dir.$file,$file);
            }
            $zip->close();
            ///Then download the zipped file.
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename='.$zip_name);
            header('Content-Length: ' . filesize($zip_name));
            readfile($zip_name);
            unlink($zip_name);
        }
        
    }


?>