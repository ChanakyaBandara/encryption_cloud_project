<?php
date_default_timezone_set("asia/colombo");
require_once 'vendor/autoload.php';
require 'dbconnect.php';
require 'file_upload.php';
require 'encription.php';
require 'responce.php';

define('orginal_dir','../LocalStorage/orginal_files');
define('encrypted_local_dir','../LocalStorage/encrypted_files_local');
define('encrypted_cloud_dir','../LocalStorage/encrypted_files_cloud');
define('decrypted_dir','../LocalStorage/decrypted_files');

	if(isset($_POST['loadFileTbl'])) {
		$db = new DbConnect;
		$conn = $db->connect();
		$stmt = $conn->prepare("SELECT * FROM `file` where `orginal_name` ORDER BY FID DESC;");
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
		echo json_encode(Responce::withData($result));
	}
	
    if(isset($_POST['addFileToLocal'])){
		
		$remark = $_POST['remaek'];

		$file_new_name ="0";
		$file_orginal_name ="0";
		
		if ($_FILES['file']['size'] <> 0){
			$file = $_FILES['file'];
			$allowd = array('xlsx','xls','docx');
			$fileDestination = orginal_dir;
			$file_orginal_name = $file['name'];
			$file_new_name = uploadfile($file,$allowd,$fileDestination);
		}


        $db = new DbConnect;
		$sql = "INSERT INTO `file`(`orginal_name`, `file_code`, `remark`, `pass_key`, `status`) VALUES (\"" . $file_orginal_name . "\",\"" . $file_new_name . "\",\"" . randomPassword() . "\",\"" . $remark . "\",1);";

		echo $sql;

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
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode(Responce::withData($result));
    	}
	}

	if(isset($_POST['encryptFile'])){
		if(encryptFileWithKey(orginal_dir.$_POST['encryptFile'],orginal_dir.$_POST['encryptFile'],$_POST['key'])){
			echo json_encode(Responce::withData($_POST['encryptFile']));
		}else{
			echo json_encode(Responce::withError(500,"Internal server error"));
		}
	}

	if(isset($_POST['addFileToCloud'])){

	}

	if(isset($_POST['getFileFromCloud'])){

	}

	if(isset($_POST['decryptFile'])){
		if(decryptFileWithKey(orginal_dir.$_POST['decryptFile'],orginal_dir.$_POST['decryptFile'],$_POST['key'])){
			echo json_encode(Responce::withData($_POST['decryptFile']));
		}else{
			echo json_encode(Responce::withError(500,"Internal server error"));
		}
	}


	function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()<>?';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 20; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
	
	

?>