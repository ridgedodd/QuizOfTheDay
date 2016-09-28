<?php
	session_start();
	$newID = $_POST["newID"];
	$newPassword = $_POST["newPASSWORD"];
	$usersptr = fopen("users.txt", "a+");
	while(($line = fgets($usersptr)) !== false) {
		$line = trim($line);
		$userInfo = explode("#", $line);
		if ($userInfo[0] === $newID) {
			$_SESSION["IDTaken"] = 1;
			header("location: http://localhost/Quiz-of-the-Day/newUser.php");
		}
	}
	
	if($_SESSION["IDTaken"] == 0) {
		fwrite($usersptr, "\r\n".$newID."#".$newPassword);
		echo "new user!!";
		header("location: http://localhost/Quiz-of-the-Day/loginHome.php");
	}
	
?>