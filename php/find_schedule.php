<?php
  include 'globals.php';

	session_start();

	$db = dbConnect();

  $query_string = $_SERVER["QUERY_STRING"];
	if (strpos($query_string, "clear_results") !== false) {
		clear_results();
	} else {
		retrieveSchedule($db);
	}
	
	function retrieveSchedule($db) {
		$GTID = htmlspecialchars($_POST["tutor_id"]);
		$formattedResult = array();
	  
		$query = sprintf("SELECT Hires.School, Hires.Number, Hires.Time, " .
						"Hires.Weekday, Student.Name, Student.Email " .
						"FROM Hires, Student " .
						"WHERE Hires.GTID_Tutor = '%s' AND\n" .
						"Hires.GTID_Undergraduate = Student.GTID ",
						mysql_real_escape_string($GTID));
						   
		$result = $db->query($query);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "<br/>";
			$message .= 'Whole query: ' . $query . "<br/>";
			die($message);
		}

		print('<h1>Tutor Schedule Results for GTID = ' . $GTID . '</h1>');

		$formattedResult = array();
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$name = $row["name"];
			$email = $row["email"];
			$day = $row["weekday"];
			$time = $row["time"];
			$school = $row["school"];
			$number = $row["number"];
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
    $name_result = $db->query($name_query);
    $tutor_schedule_name = "";
    while ($row = $name_result->fetch(PDO::FETCH_ASSOC)) {
      $tutor_schedule_name = $row["name"];
      break;
    }

    $_SESSION["tutor_schedule"] = $formattedResult;
    $_SESSION["tutor_schedule_name"] = $tutor_schedule_name;
    header("Location: ../views/tutor_schedule_view.php");
    die();
  }
	  
  function clear_results() {
    unset($_SESSION["tutor_schedule"]);
    unset($_SESSION["tutor_schedule_name"]);
    header("Location: ../views/menu_view.php");
    die();
  }
?>
