<?php
	//IMPORTANT: CAN ONLY ENTER PHP IF AT LEAST ONE SEMESTER IS CHOSEN
	session_start();
	
	$database = "4400_project_db";
	$con = mysql_connect("localhost", "root", "mysql");
	@mysql_select_db($database) or die("Unable to select database");

	//obtain semester's desired from user
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
	
	//Find course school and number of semesters with graduate tutors
	if( $numSemesters == 1 ) {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_time_slot, Tutors, Graduate " .
					"WHERE Tutor_time_slot.GTID = Tutors.GTID_tutor AND Tutors.GTID_tutor = Graduate.GTID "  .
					"AND Tutor_time_slot.Semester = '%s';",
					mysql_real_escape_string($semesterSelection[0]));	
	}
	
	else if( $numSemesters == 2 ) {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_time_slot, Tutors, Graduate " .
					"WHERE Tutor_time_slot.GTID = Tutors.GTID_tutor AND Tutors.GTID_tutor = Graduate.GTID "  .
					"AND (Tutor_time_slot.Semester = '%s' " .
					"OR Tutor_time_slot.Semester = '%s') ",
					mysql_real_escape_string($semesterSelection[0]),	
					mysql_real_escape_string($semesterSelection[1]));
	}
	else {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_time_slot, Tutors, Graduate " .
					"WHERE Tutor_time_slot.GTID = Tutors.GTID_tutor AND Tutors.GTID_tutor = Graduate.GTID "  .
					"AND (Tutor_time_slot.Semester = '%s' " .
					"OR Tutor_time_slot.Semester = '%s' " .
					"OR Tutor_time_slot.Semester = '%s') ",
					mysql_real_escape_string($semesterSelection[0]),
					mysql_real_escape_string($semesterSelection[1]),
					mysql_real_escape_string($semesterSelection[2]));
	}
	
	$result = mysql_query($query);
	$row1 = array();
	$formattedResult = array();
	while($row = mysql_fetch_assoc($result)) {
	  print(implode(", ", $row) . "<br/>");
		$school = $row["School"];
		$number = $row["Number"];
		$course = $school . " " . $number;
		$temp = $course;
		$AvgTA = 0;
		$AvgNoNTA = 0;
		$countTA = 0;
		$countNonTA = 0;
		
		$i = 0;
		while($i < $numSemesters) {
			if(isSemesterValid($semesterSelection[$i], $school, $number)) {
				
				$query = sprintf("SELECT COUNT( DISTINCT Tutors.GTA) as NUM_GTA, AVG(Rates.Num_Evaluation) as AVG_Eval\n" .
						"FROM Tutors, Rates, Graduate, Tutor_Time_Slot\n" .
						"WHERE Tutors.GTA = 1 AND Tutors.GTID_Tutor = Graduate.GTID " .
						"AND Tutors.school = '%s' AND Rates.school = '%s' " .
						"AND Tutors.number = '%s' AND Rates.number = '%s'  " .
						"AND Tutor_time_slot.Semester = '%s';",
						mysql_real_escape_string($school),
						mysql_real_escape_string($school),
						mysql_real_escape_string($number),
						mysql_real_escape_string($number),
						mysql_real_escape_string($semesterSelection[$i]));
				$resultGTA = mysql_query($query);
				$row1 = mysql_fetch_assoc($resultGTA);
				$numGTA = $row1["NUM_GTA"];
				$avgRatingGTA = $row1["AVG_Eval"];
				if(is_null($avgRatingGTA)){
					$avgRatingGTA = "N/A";
				}
				else{
					$countTA++;
					$AvgTA = $AvgTA + $avgRatingGTA;
				}
				
				$query = sprintf("SELECT COUNT( DISTINCT Tutors.GTA) as NUM_GTA, AVG(Rates.Num_Evaluation) as AVG_Eval\n" .
						"FROM Tutors, Rates, Graduate, Tutor_Time_Slot\n" .
						"WHERE Tutors.GTA = 0 AND Tutors.GTID_Tutor = Graduate.GTID " .
						"AND Tutors.school = '%s' AND Rates.school = '%s' " .
						"AND Tutors.number = '%s' AND Rates.number = '%s'  " .
						"AND Tutor_time_slot.Semester = '%s';",
						mysql_real_escape_string($school),
						mysql_real_escape_string($school),
						mysql_real_escape_string($number),
						mysql_real_escape_string($number),
						mysql_real_escape_string($semesterSelection[$i]));
				$resultGTA = mysql_query($query);
				$row1 = mysql_fetch_assoc($resultGTA);
				$numTA = $row1["NUM_GTA"];
				$avgRatingTA = $row1["AVG_Eval"];
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

  header("Location: ../html/admin_summary_report.html");
  die();
	
	//Check if there are courses return for semester
	//Return true if yes, else false
	function isSemesterValid($semesterSelection, $school, $number) {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_time_slot, Tutors, Graduate " .
					"WHERE (Tutor_time_slot.GTID = Tutors.GTID_tutor AND Tutors.GTID_tutor = Graduate.GTID) "  .
					"AND Tutor_time_slot.Semester = '%s';",
					mysql_real_escape_string($semesterSelection));	
		$resultSem = mysql_query($query);
		
		while($row = mysql_fetch_assoc($resultSem)) {
			if($row["School"] == $school AND $row["Number"] == $number) {
				return true;
			}
		}
			return false;
	}
?>
