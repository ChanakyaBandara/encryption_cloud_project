<?php 

define('orginal_dir','../LocalStorage/orginal_files');
define('encrypted_local_dir','../LocalStorage/encrypted_files_local');
define('encrypted_cloud_dir','../LocalStorage/encrypted_files_cloud');
define('decrypted_dir','../LocalStorage/decrypted_files');
	class DbConnect {
		private $host = 'localhost';
		private $dbName = 'encryption_cloud_project';
		private $user = 'root';
		private $pass = '';

		public function connect() {
			try {
				$conn = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch( PDOException $e) {
				echo 'Database Error: ' . $e->getMessage();
			}
		}
	}
 ?>