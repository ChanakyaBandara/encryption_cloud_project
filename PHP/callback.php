<?php
require_once 'cloud-config.php';
  
try {
    $adapter->authenticate();
    $token = $adapter->getAccessToken();
    $db = new DB();
    $db->update_access_token(json_encode($token));
    echo'<script language="javascript">
        window.location.href = "../ecp_dashboard.html"
        </script>';
}
catch( Exception $e ){
    echo $e->getMessage();
} 