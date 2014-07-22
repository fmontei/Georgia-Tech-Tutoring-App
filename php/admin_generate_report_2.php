<?php
	//IMPORTANT: CAN ONLY ENTER PHP IF AT LEAST ONE SEMESTER IS CHOSEN
	session_start();
	
	$database = "4400_project_db";
	$con = mysql_connect("localhost", "root", "mysql");
	@mysql_select_db($database) or die("Unable to select database");

	//obtain semester's desired from user
	$semesterSelection = $_GET["semesterSelection"];
	$numSemesters = count($semesterSelection);
	$formattedResult = array();
	
	//Find course school and number of semesters
	if( $numSemesters == 1 ) {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_time_slot, Tutors, Graduate " .
					"WHERE (Tutor_time_slot.GTID = Tutors.GTID_tutor = Graduate.GTID) "  .
					"AND Tutor_time_slot.Semester = '%s';"
					mysql_real_escape_string($semesterSelection[0]));	
	}
	
	else if( $numSemesters == 2 ) {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_time_slot, Tutors, Graduate " .
					"WHERE (Tutor_time_slot.GTID = Tutors.GTID_tutor = Graduate.GTID) "  .
					"AND (Tutor_time_slot.Semester = '%s' " .
					"OR Tutor_time_slot.Semester = '%s') ",
					mysql_real_escape_string($semesterSelection[0]),	
					mysql_real_escape_string($semesterSelection[1]));
	}
	else {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_time_slot, Tutors, Graduate " .
					"WHERE (Tutor_time_slot.GTID = Tutors.GTID_tutor = Graduate.GTID) "  .
					"AND (Tutor_time_slot.Semester = '%s' " .
					"OR Tutor_time_slot.Semester = '%s' " .
					"OR Tutor_time_slot.Semester = '%s') ",
					mysql_real_escape_string($semesterSelection[0]),
					mysql_real_escape_string($semesterSelection[1]),
					mysql_real_escape_string($semesterSelection[2]));
	}
	
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result)) {
		$school = $row["School"];
		$number = $row["Number"];
		$course = $school . " " . $number;
		$temp = $course;
		$AvgTA = 0;
		$AvgNoNTA = 0;
		$countTA = 0;
		$countNonTA = 0;
		
		$i = 0;
		while(i < $numSemesters) {
			if(isSemesterValid($semesterSelection[i]) {
				$resultGTA = obtainTAEvaluations($semesterSelection[i], 1, $school, $number);
				$row1 = mysql_fetch_assoc($resultGTA);
				$numGTA = $row["NUM_GTA"];
				$avgRatingGTA = $row["AVG_Eval"];
				if(is_null($avgRatingGTA)){
					$avgRatingGTA = "N/A";
				}
				else{
					$countTA++;
					$AvgTA = $AvgTA + $AvgTA;
				}
				
				$resultTA = obtainTAEvaluations($semesterSelection[i], 0, $school, $number);
				$row1 = mysql_fetch_assoc($resultGTA);
				$numTA = $row["NUM_GTA"];
				$avgRatingTA = $row["AVG_Eval"];
				if(is_null($avgRatingTA)){
					$avgRatingGTA = "N/A";
				}	
				else{
					$countNonTA++;
					$AvgNoNTA = $AvgNoNTA + $AvgNoNTA;
				}				
				
				array_push($formattedResult, array( "Course" => $temp,
													"Semester" => $semesterSelection[i],
													"TA" => $numGTA,
													"Avg Rating" => $avgRatingGTA,
													"non TA" => $numTA,
													"Avg Rating" => $avgRatingTA));
				if($temp === $course) {
					$temp = " ";
				}
				
			}
			$i++;
		}
		if($AvgTA > 0){
			$AvgTA = $AvgTA / $countTA;
		}
		if($AvgNoNTA > 0){
			$AvgNoNTA = $AvgNoNTA / $countNonTA;
		}
		array_push($formattedResult, array( "Course" => " ",
													"Semester" => "Avg",
													"TA" => " ",
													"Avg Rating" => $AvgTA,
													"non TA" => " ",
													"Avg Rating" => $AvgNoNTA));
	}
	
	//Check if there are courses return for semester
	//Return true if yes, else false
	function isSemesterValid($semesterSelection) {
		$query = sprintf("SELECT DISTINCT Tutors.School, Tutors.Number	" .
					"FROM Tutor_time_slot, Tutors, Graduate " .
					"WHERE (Tutor_time_slot.GTID = Tutors.GTID_tutor = Graduate.GTID) "  .
					"AND Tutor_time_slot.Semester = '%s';"
					mysql_real_escape_string($semesterSelection));	
		$resultSem = mysql_query($query);
		return $resultSem;
	}

	function obtainTAEvaluations($semesterSelection, $GTA, $school, $number) {
		//obtains average evaluation of TA tutors
		$query = sprintf("SELECT COUNT(tutors.GTA) as NUM_GTA, AVG(Rates.Num_Evaluation) as AVG_Eval" .
						"FROM Tutors, Rates, Graduate, Tutor_time_slot " .
						"WHERE Tutors.GTA = '%s' AND Tutors.GTID_Tutor = Graduate.GTID " .
						"AND Tutors.school = '%s' AND Rates.school = '%s' " .
						"AND Tutors.number = '%s' AND Rates.number = '%s'  " .
						"AND Tutor_time_slot.Semester = '%s' ",
						mysql_real_escape_string($GTA),
						mysql_real_escape_string($school),
						mysql_real_escape_string($school),
						mysql_real_escape_string($number),
						mysql_real_escape_string($number),
						mysql_real_escape_string($semesterSelection));
		$resultGTA = mysql_query($query);
		if($resultGTA) {
			return true; 
		}
		else {
			return false;
		}
	}
	
	
?>
