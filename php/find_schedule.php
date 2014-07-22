<?php
	session_start();
	$database = "4400_project_db";
    $con = mysql_connect(localhost, "root", "mysql");
    @mysql_select_db($database) or die("Unable to select database");
	
	if(strpos($_SERVER["QUERY_STRING"], "clear_results") == true) {
		clear_results();
	}
	else
	{
		retrieveSchedule();
	}
	
	function retrieveSchedule() {
		$GTID = htmlspecialchars($_GET["tutor_id"]);
		$query  = explode('&', $_SERVER['QUERY_STRING']);
		$formattedResult = array();
	  
		$query = sprintf("SELECT Hires.School, Hires.Number, Hires.Time, " .
						"Hires.Weekday, Student.Name, Student.Email " .
						"FROM Hires, Student " .
						"WHERE Hires.GTID_Tutor = '%s' AND\n" .
						"Hires.GTID_Undergraduate = Student.GTID ",
						mysql_real_escape_string($GTID));
						   
		$result = mysql_query($query);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "<br/>";
			$message .= 'Whole query: ' . $query . "<br/>";
			die($message);
		}
		
		while($row = mysql_fetch_assoc($result)) {
			$name = $row["Name"];
			$email = $row["Email"];
			$day = $row["Weekday"];
			$time = $row["Time"];
			$school = $row["School"];
			$number = $row["Number"];
			$pos = strrpos($name, " ");
			$firstName = substr($name, 0, $pos);
			$lastName = substr($name, $pos);
			$course = $school ." ". $number;
			
			array_push($formattedResult, array("Day" => $day,
											   "Time" => $time,
											   "First" => $firstName,
											   "Last" => $lastName,
											   "Email" => $email,
											   "Course" => $course));
		  }
		  $_SESSION["tutor_schedule"] = $formattedResult;
		  header("Location: ../html/tutor_schedule.html");
	  }
	  
	  function clear_results() {
		unset($_SESSION["tutor_schedule"]);
		header("Location: ../html/menu.html");
	  }
?>
