<?php
require 'dbconnect.php';

if(isset($_POST['addUser'])) {
    $txtRegName = $_POST['txtRegName'];
    $txtRegEmail = $_POST['txtRegEmail'];
    $txtRegPass = $_POST['txtRegPass'];

    $pdo_db = new DbConnect;
    $hashed = password_hash($txtRegPass,PASSWORD_BCRYPT);
    $sql = "INSERT INTO  `user`( `email`, `name`,`Password`) VALUES ('$txtRegEmail','$txtRegName','$hashed')";

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

?>