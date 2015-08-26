<?php
	include 'globals.php';

	$school = 'ECE';
	$courseNumber = '5000';
	$preferredDays = 'Thursday';
	$preferredTimes = '9am';
	$currentSemester = 'FALL';

	db_connect(); // From globals.php
	
	//query
	$sql_tutors = sprintf("SELECT Student.GTID, Student.Name, Student.Email
					FROM Student, Tutors, Tutor_Time_Slot
					WHERE Student.GTID = Tutors.GTID_Tutor 
					AND Tutor_Time_Slot.GTID = Tutors.GTID_Tutor
					AND Tutors.School = '%s'
					AND Tutors.Number = '%s'
					AND Tutor_Time_Slot.Time = '%s'
					AND Tutor_Time_Slot.Weekday = '%s'
					AND Tutor_Time_Slot.Semester = '%s';",
					mysql_real_escape_string($school),
					mysql_real_escape_string($courseNumber),
					mysql_real_escape_string($preferredTimes),
					mysql_real_escape_string($preferredDays),
					mysql_real_escape_string($currentSemester));
	//print($sql);
	$result = mysql_query($sql_tutors);
	$tutors = array();
	while ($row = mysql_fetch_assoc($result)) {
		$GTID = $row['GTID'];
		$name = $row['Name'];
		$email = $row['Email'];
		array_push($tutors, array("GTID" => $GTID, "Name" => $name, "Email" => $email));
	}
	
	for ($i = 0; $i < count($tutors); $i++) {
	$GTID = $tutors[$i]['GTID'];
	// ECHO '<br>';
	$sql_prof_eval = sprintf("SELECT R.GTID_Tutor, AVG(R.Num_Evaluation) AS Avg_Prof_Rating, 
							 COUNT(R.Num_Evaluation) AS Num_Professors
							 FROM Recommends R
							 WHERE R.GTID_Tutor = '%s';",
							 mysql_real_escape_string($GTID));
	$result = mysql_query($sql_prof_eval);
	while ($row = mysql_fetch_assoc($result)) {
		$tutors[$i] = array_merge($tutors[$i], array('Avg_Prof_Rating' => $row['Avg_Prof_Rating'], 'Num_Professors' => $row['Num_Professors']));
	}
	// print($sql_prof_eval);
	// ECHO '<br>';
	}
	// print_r($tutors);
	
	for ($i = 0; $i < count($tutors); $i++) {
	$GTID = $tutors[$i]['GTID'];
	// ECHO '<br>';
	$sql_student_eval = sprintf("SELECT R.GTID_Tutor, AVG(R.Num_Evaluation) AS Avg_Student_Rating, 
							COUNT(R.Num_Evaluation) AS Num_Students
							FROM Rates R
							WHERE R.GTID_Tutor = '%s';",
							mysql_real_escape_string($GTID));
	$result = mysql_query($sql_student_eval);
	while ($row = mysql_fetch_assoc($result)) {
		$tutors[$i] = array_merge($tutors[$i], array('Avg_Student_Rating' => $row['Avg_Student_Rating'], 'Num_Students' => $row['Num_Students']));
	}
	// print($sql_prof_eval);
	// ECHO '<br>';
	}
	print_r($tutors);
	
?>