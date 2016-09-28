<?php
	session_start();
	if(isset($_COOKIE["ID"])) {
		setcookie("ID", "", time()-3600);
	}
	header("location: http://localhost/Quiz-of-the-Day/loginHome.php");
?>