<?php
require 'dbconnect.php';

function creat_user($nic,$name,$Password){
    $db = new DbConnect;
    $hashed = password_hash($Password,PASSWORD_BCRYPT);
    $sql = "INSERT INTO  `users`( `email`, `name`,`Password`) VALUES ('$nic','$name','$hashed')";

    if(!$conn = $db->connect()){
        echo "SQL Error";
        exit();
    }
    else {
        $conn->exec($sql);
        $last_id = $conn->lastInsertId();
        return $last_id;
    }
}

if(isset($_POST['addUser'])) {
    $txtRegName = $_POST['txtRegName'];
    $txtRegEmail = $_POST['txtRegEmail'];
    $txtRegPass = $_POST['txtRegPass'];



    $db = new DbConnect;
    $hashed = password_hash($txtRegPass,PASSWORD_BCRYPT);
    $sql = "INSERT INTO  `user`( `email`, `name`,`Password`) VALUES ('$txtRegEmail','$txtRegName','$hashed')";

    if(!$conn = $db->connect()){
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

?>