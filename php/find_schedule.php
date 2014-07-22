<?php
	session_start();
	$database = "4400_project_db";
  $con = mysql_connect("localhost", "root", "mysql");
  @mysql_select_db($database) or die("Unable to select database");

  $query_string = $_SERVER["QUERY_STRING"];
	if (strpos($query_string, "clear_results") !== false) {
		clear_results();
	} else {
		retrieveSchedule();
	}
	
	function retrieveSchedule() {
		$GTID = htmlspecialchars($_POST["tutor_id"]);
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

		print('<h1>Tutor Schedule Results for GTID = ' . $GTID . '</h1>');

		$formattedResult = array();
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
      print($firstName . " " . $lastName . "<br /><br />");
    }

    $name_query = sprintf("SELECT Student.Name FROM Student\n" .
                          "WHERE Student.GTID = '%s'",
                          mysql_real_escape_string($GTID));
    $name_result = mysql_query($name_query);
    $tutor_schedule_name = "";
    while ($row = mysql_fetch_assoc($name_result)) {
      $tutor_schedule_name = $row["Name"];
      break;
    }

    $_SESSION["tutor_schedule"] = $formattedResult;
    $_SESSION["tutor_schedule_name"] = $tutor_schedule_name;
    header("Location: ../html/tutor_schedule.html");
    die();
  }
	  
  function clear_results() {
    unset($_SESSION["tutor_schedule"]);
    unset($_SESSION["tutor_schedule_name"]);
    header("Location: ../html/menu.html");
    die();
  }
?>
