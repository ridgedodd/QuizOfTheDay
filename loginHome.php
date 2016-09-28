<?php
	session_start();
	
	#if user has a cookie saved
	if(isset($_COOKIE["ID"])) {
		$_SESSION["ID"] = $_COOKIE["ID"];
		header("location: http://localhost/Quiz-of-the-Day/testing.php");
	}
?>
<html>
<head>
	<title>Quiz of the Day</title>
</head>
<body>
	<b>Welcome to the Quiz of the Day!</b>
	<br /><br />
	<form action = "login.php"
		method = "POST">
	<b>USER ID:</b>
	<input type = "text" name = "ID" size = "30" maxlength = "30">
	<br /><br />
	<b>PASSWORD:</b>
	<input type = "text" name = "PASSWORD" size = "30" maxlength = "30">
	<br /><br />
	<b>Keep me logged in?</b>
	<input type = "checkbox" name = "keep">
	<br /><br />
	<input type = "submit" value = "Submit">
	</form>
	<form action = "newUser.php"
		method = "POST">
	<input type = "submit" value = "Click here if new user">
	</form>
</body>
</html>