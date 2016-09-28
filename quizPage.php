<?php
	session_start();
	date_default_timezone_set('America/New_York');
	$strtDay = date("m-d-Y");
	$usrName = $_SESSION["ID"];
	
	#store cookie with timestamp
	setcookie("".$usrName."lastDayTaken", $strtDay, time()+86400);
?>
	<?php
	
		#generates the next question if there is one
		function next_question() {
			
			#sets up file to be read
			$quizFileName = $_SESSION["quizFileName"];
			$quizPtr = fopen($quizFileName, "r");
			if(!(isset($_SESSION["questionNo"]))) {
				$_SESSION["questionNo"] = 1;
			}
			else {
				$_SESSION['questionNo'] += 1;
			}
			$questionNo = $_SESSION["questionNo"];
			
			#sets up results for current session
			if(!(isset($_SESSION['numCorrect']))) {
				$_SESSION['numCorrect'] = 0;
				$_SESSION["numInc"] = 0;
			}
			
			#gets user's answer and compares it to correct answer
			if((isset($_POST["answer"]))) {
				$enteredAns = $_POST["answer"];
				$correctAns = $_SESSION["correctAns"];
				$correctAnsStr = $_SESSION["correctAnsStr"];
				if($enteredAns === $correctAns) {
					echo "You are correct!";
					echo "<br>";
					$_SESSION["numCorrect"] += 1;
				}
				else {
					echo "Sorry, the answer was ". $correctAnsStr;
					echo "<br>";
					$_SESSION["numInc"] += 1;
				}
			}
			
			#skips to current question - ignores questions already asked
			for($x = 1; $x <= $questionNo; $x++) {
				$question = fgets($quizPtr);
			}
			
			#breaks up quiz file
			$question = trim($question);
			$questionParts = explode("#", $question);
			$answerChoices = explode(":", $questionParts[1]);
			$correctAns = $questionParts[2];
			$numAnswerChoices = count($answerChoices);
			$_SESSION["correctAnsStr"] = $answerChoices[((int)$correctAns)-1];
			$_SESSION["correctAns"] = $correctAns;
			?>
			<form name = "question"
				action = "quizPage.php"
				method = "POST">
			<?php
				echo $questionParts[0];
				echo "<br>";
			?>
			<?php
				#prints out each question in order
				for($i = 0; $i < $numAnswerChoices; $i++) {
					echo $answerChoices[$i];
					$value = $i+1;
					echo "<input type= \"radio\" name= \"answer\" value= \"$value\"> <br>";
				}
				
			?>
			<input type = "submit" value = "Submit">
			</form>
			<?php
			
		}
		function show_end() {
			echo "</html>";
		}
		function show_header() {
		?>
		<html>
		<head>
		</head>
		<?php
		}
	?>
	
	<?php
	
		#read quizzes.txt to figure out how many questions are in quiz
		$quizzesptr = fopen("quizzes.txt", "r+");

		if(flock($quizzesptr, LOCK_SH)) {
		
		while(($line = fgets($quizzesptr)) !== false) {
			$line = trim($line);
			$quizInfo = explode("#", $line);
			if ($quizInfo[0] === $_SESSION["quizFileName"]) {
				$numOfQuestions = $quizInfo[1];
				$numOfQuestions = intval($numOfQuestions);
				$_SESSION["numOfQuestions"] = $numOfQuestions;
				break;
			}
		}
		}
		flock($quizzesptr, LOCK_UN);
		
	
		if(!(isset($_SESSION['questionNo']))) {
			show_header();
			next_question();
			show_end();
		}
		else if($_SESSION['questionNo'] < $numOfQuestions){
			show_header();
			next_question();
			show_end();
		}
		else {
			#prints out result of previous question
			$enteredAns = $_POST["answer"];
			$correctAns = $_SESSION["correctAns"];
			if($enteredAns === $correctAns) {
				echo "You are correct!";
				$_SESSION["numCorrect"] += 1;
			}
			else {
				echo "Sorry, the answer was ". $_SESSION["correctAnsStr"];
				$_SESSION["numInc"] += 1;
			}
			echo "<br>";
			echo "Quiz is done<br><br>";
			echo "YOUR RESULTS:<br>";
			echo "Num Correct: ".$_SESSION["numCorrect"];
			echo "<br>";
			echo "Total Questions: ".$_SESSION["numOfQuestions"];
			$corr = (int)$_SESSION["numCorrect"];
			$inc = (int)$_SESSION["numInc"];
			$totques = $corr + $inc;
			$sessionAverage = ($corr / (float)$totques)*100;
			echo "<br>";
			echo "Average: ".$sessionAverage." %";
			echo "<br><br>";
			
			
			#WRITING OVER SPECIFIC LINE IN FILE
			
			$arr = file("quizzes.txt");
			foreach ($arr as &$linequiz) {
				$linequiz = trim($linequiz);
				if (strpos("x".$linequiz, $_SESSION["quizFileName"])) {
					$linequizpart = explode("#", $linequiz);
					$totNumTakers = $linequizpart[2];
					$totNumCorr = $linequizpart[3];
					$totNumInc = $linequizpart[4];
					$totAllTimeQuestions = $totNumCorr + $totNumInc;
					$linequizpart[2] += 1;
					$linequizpart[3] += (int)$_SESSION["numCorrect"];
					$linequizpart[4] += (int)$_SESSION["numInc"];
					$totNumTakers = $linequizpart[2];
					$totNumCorr = $linequizpart[3];
					$totNumInc = $linequizpart[4];
					$totAllTimeQuestions = $totNumCorr + $totNumInc;
					$totPerc = ($totNumCorr / (float)$totAllTimeQuestions)*100;
					
					$linequiz = implode("#",$linequizpart);
				}
			}
			
			if(flock($quizzesptr, LOCK_EX)) {
			
				$newFile = implode("\r\n",$arr);
				rewind($quizzesptr);
		
				fwrite($quizzesptr, $newFile);
			}
			flock($quizzesptr, LOCK_UN);
		
			#DONE UPDATING FILE
			
			echo "OVERALL RESULTS:<br>";
			echo "".$totNumTakers. " total takers<br>";
			echo "Num Correct: ".$totNumCorr;
			echo "<br>";
			echo "Total Questions: ".$totAllTimeQuestions;
			echo "<br>";
			echo "Average: ".$totPerc." %";
			?>
			<br>
			<form action = "testing.php">
				<input type = "submit" value = "Return to Home">
			</form>
			<?php
		}
	?>
