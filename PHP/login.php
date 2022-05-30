<?php
	require 'dbconnect.php';

	if(isset($_POST['log-in'])){
		
		$username = $_POST['uname'];
		$password = $_POST['pword'];

		if(empty($username) || empty($password)){

			echo'<script language="javascript">
						window.alert("Please fill the empty fields")
						window.location.href = "../ecp_login.html"
						</script>';
						exit();
		}else{

			$sql = "SELECT * FROM user WHERE email=\"" . $username . "\"";

			$pdo_db = new DbConnect;

			if(!$conn = $pdo_db->connect()){

				echo'<script language="javascript">
						window.alert("SQL ERROR. Please check the SQL code ")
						window.location.href = "../ecp_login.html"
						</script>';
						exit();
						
			}else{

				$stmt = $conn->prepare($sql);
				$stmt->execute();
				
				if($result = $stmt->fetchAll(PDO::FETCH_ASSOC)){
					
					$passveri;
					$ID;
					$Sts;

					foreach ($result as $rows) {
                        $passveri = $rows['Password'];
                        $UID = $rows['UID'];
                        $name = $rows['name'];
                    }

					$pwdcheck = true;

					if (password_verify($password, $passveri)){
						$pwdcheck = true;
					}

					if($pwdcheck == false){

						echo'<script language="javascript">
						window.alert("You entered a Wrong Password !")
						window.location.href = "../ecp_login.html"
						</script>';
						exit();
						
					}else if($pwdcheck == true){
						
						echo '<script language="javascript">
									localStorage.setItem("UID","'.$UID.'");
									localStorage.setItem("Name","'.$name.'");
									localStorage.setItem("Email","'.$username.'");
									window.location.href = "callback.php"
									</script>';
									exit();

					}else{
						echo'<script language="javascript">
						window.alert("You entered a Wrong Password !")
						window.location.href = "../ecp_login.html"
						</script>';
						exit();
						
					}

				}else{
					echo'<script language="javascript">
						window.alert("Username incorrect! Please check the username and try again..")
						window.location.href = "../ecp_login.html"
						</script>';
						exit();
				}
			}
		}
	}

	if(isset($_POST['register'])){
		
		$username = $_POST['txtRegEmail'];
		$name = $_POST['txtRegName'];
		$password = $_POST['txtRegPass'];

		if(empty($username) || empty($name) || empty($password)){

			echo'<script language="javascript">
						window.alert("Please fill the empty fields")
						window.location.href = "../register.html"
						</script>';
						exit();
		}else{

			$pdo_db = new DbConnect;
			$hashed = password_hash($password, PASSWORD_BCRYPT);
			$sql = "INSERT INTO `user`(`email`, `name`, `password`) VALUES ('$username','$name','$hashed')";

			if(!$conn = $pdo_db->connect()){
				echo "SQL Error";
				exit();
			}
			else {
				$stmt = $conn->prepare($sql);
				$stmt->execute();
				echo '<script language="javascript">
				window.alert("Succesfully added!");
				window.location.href = "../ecp_login.html"
				</script>';
				exit();
			}
		}
	}
	
?>