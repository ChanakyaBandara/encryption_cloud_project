<?php
	
	session_start();
	session_unset();
	session_destroy();
	echo '<script language="javascript">
			localStorage.clear();
			window.location.href = "../ecp_login.html"
			</script>';
			exit();

?>