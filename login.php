<?php
	session_start();
?>
<html>
<head>
</head>
<body>
<?php
	if(isset($_SESSION['ID'])) {
		session_destroy();
		session_start();
	}
	
	#gathers info from login page
	$enteredID = $_POST["ID"];
	$enteredPASS = $_POST["PASSWORD"];
	$enteredKeep = $_POST["keep"];
	$_SESSION["enteredKeep"] = $enteredKeep;
	if($enteredKeep == "on") {
		setcookie("ID", $enteredID);
		setcookie("Pass", $enteredPass);
	}
	$_SESSION["ID"] = $enteredID;
	$_SESSION["Password"] = $enteredPASS;
	
	#checks to see if user is valid
	$fileptr = fopen("users.txt", "r");
	$validUser = false;
	while(($line = fgets($fileptr)) !== false) {
		$line = trim($line);
		$UserToCheck = explode("#", $line);
		if($UserToCheck[0] === $enteredID && $UserToCheck[1] === $enteredPASS) {
			$validUser = true;
		}
	}
	
	#log user in
	if($validUser) {
		header("location: http://localhost/Quiz-of-the-Day/testing.php");
	}
	else {
		echo "Wrong username/password";
	}
?>
</body>
</html>