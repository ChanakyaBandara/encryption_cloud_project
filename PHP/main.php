<?php
date_default_timezone_set("asia/colombo");
require_once 'vendor/autoload.php';
require 'dbconnect.php';
require 'file_upload.php';
require 'encription.php';
require 'responce.php';

error_reporting(E_ALL ^ E_WARNING);

define('orginal_dir','../LocalStorage/orginal_files');
define('encrypted_local_dir','../LocalStorage/encrypted_files_local');
define('encrypted_cloud_dir','../LocalStorage/encrypted_files_cloud');
define('decrypted_dir','../LocalStorage/decrypted_files');

	if(isset($_POST['loadFileTbl'])) {
		$db = new DbConnect;
		$conn = $db->connect();
		$stmt = $conn->prepare("SELECT * FROM `file` where `UID` = " . $_POST['loadFileTbl'] . " ORDER BY FID DESC;");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		//echo json_encode(Responce::withData($result));
		echo json_encode($result);
	}

    if(isset($_POST['loadfileTblSearch'])) {
		$db = new DbConnect;
		$conn = $db->connect();
		$stmt = $conn->prepare("SELECT * FROM `file` where `orginal_name` LIKE \"%" . $_POST['loadfileTblSearch'] . "%\" ;");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		//echo json_encode(Responce::withData($result));
		echo json_encode($result);
	}
	
    if(isset($_POST['remark']) || isset($_POST['file'])){
		
		$remark = $_POST['remark'];
		$UID = $_POST['UID'];

		$file_new_name ="0";
		$file_orginal_name ="0";
		
		if ($_FILES['file']['size'] <> 0){
			$file = $_FILES['file'];
			$allowd = array('xlsx','xls','docx','pdf','txt');
			$fileDestination = orginal_dir;
			$file_orginal_name = $file['name'];
			$file_new_name = uploadfile($file,$allowd,$fileDestination);
		}

		$pass_key = randomPassword();

        $db = new DbConnect;
		$sql = "INSERT INTO `file`(`orginal_name`, `file_code`, `remark`, `pass_key`, `status`, `UID`) VALUES (\"" . $file_orginal_name . "\",\"" . $file_new_name . "\",\"" . $remark . "\",\"" . $pass_key . "\",1," . $UID . ");";

		if(!$conn = $db->connect())
			
		{
			echo'<script language="javascript">
					window.alert("SQL ERROR. Please check the SQL code ")
					</script>';
					exit();
		}
		else
		{
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$FID = $conn->lastInsertId();
			$myObj->FID = $FID;
			$myObj->orginal_name = $file_orginal_name;
			$myObj->file_code = $file_new_name;
			$myObj->remark = $remark;
			$myObj->pass_key = $pass_key;
			echo json_encode($myObj);
    	}
	}

	if(isset($_POST['encryptFile']) && isset($_POST['pass_key'])){
		
	    $fileExt = explode('.',$_POST['encryptFile']);
		array_pop($fileExt);
		$rawName = implode('.',$fileExt);
		$orginal_file = orginal_dir.'/'.$_POST['encryptFile'];
		$encrypted_file = encrypted_local_dir.'/'.$rawName.'.bin';
		if(encryptFileWithKey($orginal_file,$encrypted_file,$_POST['pass_key'])){
			$myObj->encryptedFileName = $rawName.'.bin';
			echo json_encode($myObj);
		}else{
			echo json_encode(Responce::withError(500,"Internal server error"));
		}
	}

	if(isset($_POST['addFileToCloud'])){

	}

	if(isset($_POST['getFileFromCloud'])){

	}

	if(isset($_POST['decryptFile']) && isset($_POST['pass_key'])){

		$fileExt = explode('.',$_POST['decryptFile']);
		array_pop($fileExt);
		$rawName = implode('.',$fileExt);
		$decrypted_file = decrypted_dir.'/'.$_POST['decryptFile'];
		$encrypted_file = encrypted_cloud_dir.'/'.$rawName.'.bin';

		if(decryptFileWithKey($encrypted_file,$decrypted_file,$_POST['pass_key'])){
			$myObj->decryptedFileName = $_POST['decryptFile'];
			echo json_encode($myObj);
		}else{
			echo json_encode(Responce::withError(500,"Internal server error"));
		}
	}


	function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 20; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
