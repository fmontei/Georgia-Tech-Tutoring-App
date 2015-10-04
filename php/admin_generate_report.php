<?php
  include 'globals.php';

	session_start();
	
	$db = dbConnect();

	// Obtain semesters desired from user
	$semesterSelection = array();
	$fall_checkbox = $spring_checkbox = $summer_checkbox = "";
	if (isset($_GET["fall_checkbox"])) {
		array_push($semesterSelection, "FALL");
	}
	if (isset($_GET["spring_checkbox"])) {
		array_push($semesterSelection, "SPRING");
	}
	if (isset($_GET["summer_checkbox"])) {
		array_push($semesterSelection, "SUMMER");
	}
	$numSemesters = count($semesterSelection);
	
	// Find course, school, and number of semesters with graduate tutors
	if($numSemesters == 1) {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_Time_Slot, Tutors, Graduate " .
					"WHERE Tutor_Time_Slot.GTID = Tutors.GTID_Tutor AND Tutors.GTID_Tutor = Graduate.GTID "  .
					"AND Tutor_Time_Slot.Semester = '%s';",
					mysql_real_escape_string($semesterSelection[0]));	
	}
	
	else if($numSemesters == 2) {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_Time_Slot, Tutors, Graduate " .
					"WHERE Tutor_Time_Slot.GTID = Tutors.GTID_Tutor AND Tutors.GTID_Tutor = Graduate.GTID "  .
					"AND (Tutor_Time_Slot.Semester = '%s' " .
					"OR Tutor_Time_Slot.Semester = '%s') ",
					mysql_real_escape_string($semesterSelection[0]),	
					mysql_real_escape_string($semesterSelection[1]));
	}
	else {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_Time_Slot, Tutors, Graduate " .
					"WHERE Tutor_Time_Slot.GTID = Tutors.GTID_Tutor AND Tutors.GTID_Tutor = Graduate.GTID "  .
					"AND (Tutor_Time_Slot.Semester = '%s' " .
					"OR Tutor_Time_Slot.Semester = '%s' " .
					"OR Tutor_Time_Slot.Semester = '%s') ",
					mysql_real_escape_string($semesterSelection[0]),
					mysql_real_escape_string($semesterSelection[1]),
					mysql_real_escape_string($semesterSelection[2]));
	}
	
	$result = $db->query($query);
	$row1 = array();
	$formattedResult = array();
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  print(implode(", ", $row) . "<br/>");
		$school = $row["school"];
		$number = $row["number"];
		$course = $school . " " . $number;
		$temp = $course;
		$AvgTA = 0;
		$AvgNoNTA = 0;
		$countTA = 0;
		$countNonTA = 0;
		
		$i = 0;
		while($i < $numSemesters) {
			if(isSemesterValid($db, $semesterSelection[$i], $school, $number)) {
				
				$query = sprintf("SELECT COUNT(DISTINCT Tutors.GTA) as NUM_GTA, AVG(Rates.Num_Evaluation) as AVG_Eval\n" .
						"FROM Tutors, Rates, Graduate, Tutor_Time_Slot\n" .
						"WHERE Tutors.GTA = '1' AND Tutors.GTID_Tutor = Graduate.GTID " .
						"AND Tutors.School = '%s' AND Rates.School = '%s' " .
						"AND Tutors.Number = '%s' AND Rates.Number = '%s'  " .
						"AND Tutor_Time_Slot.Semester = '%s';",
						mysql_real_escape_string($school),
						mysql_real_escape_string($school),
						mysql_real_escape_string($number),
						mysql_real_escape_string($number),
						mysql_real_escape_string($semesterSelection[$i]));
				$resultGTA = $db->query($query);
				$retval = queryErrorHandler($db, $resultGTA);
				if ($retval === false) {
					die('Query: ' . $query . '<br />');
				}
				$row1 = $resultGTA->fetch(PDO::FETCH_ASSOC);
				$numGTA = $row1["num_gta"];
				$avgRatingGTA = $row1["avg_eval"];
				if(is_null($avgRatingGTA)){
					$avgRatingGTA = "N/A";
				}
				else{
					$countTA++;
					$AvgTA = $AvgTA + $avgRatingGTA;
				}
				
				$query = sprintf("SELECT COUNT(DISTINCT Tutors.GTA) as NUM_GTA, AVG(Rates.Num_Evaluation) as AVG_Eval\n" .
						"FROM Tutors, Rates, Graduate, Tutor_Time_Slot\n" .
						"WHERE Tutors.GTA = 0 AND Tutors.GTID_Tutor = Graduate.GTID " .
						"AND Tutors.school = '%s' AND Rates.school = '%s' " .
						"AND Tutors.number = '%s' AND Rates.number = '%s'  " .
						"AND Tutor_Time_Slot.Semester = '%s';",
						mysql_real_escape_string($school),
						mysql_real_escape_string($school),
						mysql_real_escape_string($number),
						mysql_real_escape_string($number),
						mysql_real_escape_string($semesterSelection[$i]));
				$resultGTA = $db->query($query);
				$row1 = mysql_fetch_assoc($resultGTA);
				$numTA = $row1["num_gta"];
				$avgRatingTA = $row1["avg_eval"];
				if(is_null($avgRatingTA)){
					$avgRatingTA = "N/A";
				}	
				else{
					$countNonTA++;
					$AvgNoNTA = $AvgNoNTA + $avgRatingTA;
				}				
				
				array_push($formattedResult, array( "Course" => $temp,
													"Semester" => $semesterSelection[$i],
													"TA" => $numGTA,
													"AVG Rating" => $avgRatingGTA,
													"non TA" => $numTA,
													"AVG Rating2" => $avgRatingTA));
				if($temp === $course) {
					$temp = " ";
				}
			}
			$i++;
		}
		if($AvgTA > 0){
			$AvgTA = $AvgTA / $countTA;
		}
		else if($AvgTA == 0){
			$AvgTA = "N/A";
		}
		
		if($AvgNoNTA > 0){
			$AvgNoNTA = $AvgNoNTA / $countNonTA;
		}
		else if($AvgNoNTA == 0){
			$AvgNoNTA = "N/A"; 
		}
		array_push($formattedResult, array( "Course" => " ",
													"Semester" => "AVG",
													"TA" => " ",
													"AVG Rating" => $AvgTA,
													"non TA" => " ",
													"AVG Rating2" => $AvgNoNTA));
	}

  $_SESSION["result_summary_2_results"] = $formattedResult;
  print("<h1>PRINTING RESULTS</h1>");
  foreach ($formattedResult as $row) {
    print(implode(", ", $row) . "<br />");
  }

  header("Location: ../views/admin_summary_view.php");
  die();
	
	function isSemesterValid($db, $semesterSelection, $school, $number) {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_Time_Slot, Tutors, Graduate " .
					"WHERE (Tutor_Time_Slot.GTID = Tutors.GTID_Tutor AND Tutors.GTID_Tutor = Graduate.GTID) "  .
					"AND Tutor_Time_Slot.Semester = '%s';",
					mysql_real_escape_string($semesterSelection));	
		$resultSem = $db->query($query);
		
		while($row = $resultSem->fetch(PDO::FETCH_ASSOC)) {
			if($row["school"] == $school AND $row["number"] == $number) {
				return true;
			}
		}
			return false;
	}
?>
