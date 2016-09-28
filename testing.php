<?php
	session_start();
	date_default_timezone_set('America/New_York');
?>
<html>
<head>
</head>
<body>
	<?php
		echo "Welcome, ". $_SESSION["ID"];
		
		$currDay = date("m-d-Y");
		
		#randomize the quiz each day
		$day = date("d");
		srand($day);
		$rando = rand();
		#make this a random no. by reading in contents of quizzes.txt and counting lines
		$quizNo = fmod($rando, 3);
		if($quizNo == 0) {
			$quizNo = 3;
		}
		$_SESSION["quizFileName"] = "quiz".$quizNo.".txt";
		
		
		#check if user has taken a quiz today
		$taken = 0;
		$usrName = $_SESSION["ID"];
		if(isset($_COOKIE["".$usrName."lastDayTaken"])) {
			$d = $_COOKIE["".$usrName."lastDayTaken"];
			if($currDay === $d) {
				$taken = 1;
			}
		}
	?>
	<br>
	<form action = "logout.php">
		<input type = "submit" value = "Change User / Logout"> <br>
	</form>
	<?php
	
		#show 'start quiz' button if user has not taken quiz today
		if(!$taken) {
			echo "<form  action = \"quizPage.php\"><br>";
			echo "<input type = \"submit\" value = \"Start Quiz\"><br>";
			echo "</form><br>";
		}
		else {
			echo "<br><br>You've already taken the quiz for today, try again tomorrow.";
		}
	?>
	
		
	
</body>
</html>