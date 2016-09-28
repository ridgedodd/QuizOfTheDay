<?php
	session_start();
	if(!(isset($_SESSION["IDTaken"]))) {
		$_SESSION["IDTaken"] = 0;
	}
	
?>
<html>
<body>

	<?php
		if($_SESSION["IDTaken"] == 1) {
			echo "<b>That Username is already taken, try a different one</b><br><br>";
		}
		$_SESSION["IDTaken"] = 0;
	?>
	<b>Choose a Username and Password:</b>
	<br /><br />
	<form action = "newUserLogic.php"
		method = "POST">
	<b>USER ID:</b>
	<input type = "text" name = "newID" size = "30" maxlength = "30">
	<br /><br />
	<b>PASSWORD:</b>
	<input type = "text" name = "newPASSWORD" size = "30" maxlength = "30">
	<br /><br />
	<input type = "submit" value = "Submit">
	</form>
</body>
</html>