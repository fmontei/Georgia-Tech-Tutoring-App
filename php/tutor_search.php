<?php
  include 'globals.php';

  session_start();

  $db = dbConnect();

  if (strpos($_SERVER["QUERY_STRING"], "init") !== false) {
    populateInputFields($db);
  } else if ($_GET["submitDayTimeBtn"] !== null) {
    $school = htmlspecialchars($_GET["schoolName"]);
    $courseNumber = htmlspecialchars($_GET["courseNumber"]);
    $preferredDays = htmlspecialchars($_GET["preferredDay"]);
    $preferredTimes = htmlspecialchars($_GET["preferredTime"]);

    $query = explode('&', $_SERVER['QUERY_STRING']);
    $params = array();
    foreach($query as $param) {
      list($name, $value) = explode('=', $param);
      $params[urldecode($name)][] = urldecode($value);
    }

    $preferredDayArray = findDays($params);
    $preferredTimeArray = findTimes($params);

    $formattedCourseResult = fetchAllAvailableTimeSlots($db, $school, $courseNumber,
      $preferredDayArray, $preferredTimeArray);
    $tutorQuery = getAvailableTutors($db, $school, $courseNumber, $preferredDayArray,
      $preferredTimeArray);

    $tutorResult = executeQuery($db, $tutorQuery);
    $formattedTutorResult = formatTutorResult($tutorResult);

    redirectBack($formattedCourseResult, $formattedTutorResult, $school,
      $courseNumber);
  }

  function populateInputFields($db) {
    $courseQuery = 'SELECT * FROM Course';
    $schoolResult = $db->query($courseQuery);
    $availableSchools = array();
    $availableCourses = array();
    while ($row = $schoolResult->fetch(PDO::FETCH_ASSOC))  {
      if (!in_array($row['school'], $availableSchools)) {
        array_push($availableSchools, $row['school']);    
      }
      $schoolCourse = new stdClass;
      $schoolCourse->school = $row['school'];
      $schoolCourse->course = $row['number'];
      if (!in_array($schoolCourse, $availableSchools)) {
        array_push($availableCourses, $schoolCourse);    
      }
    }
    $_SESSION['availableSchools'] = $availableSchools;
    $_SESSION['availableCourses'] = $availableCourses;
    header("Location: ../views/tutor_search_view.php");
    die();               
  }

  function findDays($params) {
    $dayArray = array();
    foreach($params as $index => $param) {
      if (strpos($index, 'preferredDay') !== FALSE)
        $dayArray = $param;
    }
    return $dayArray;
  }

  function findTimes($params) {
    $timeArray = array();
    foreach($params as $index => $param) {
      if (strpos($index, 'preferredTime') !== FALSE)
        $timeArray = $param;
    }
    return $timeArray;
  }

  function fetchAllAvailableTimeSlots($db, $schoolName, $courseNumber, $dayArray,
      $timeArray) {
    $currentSemester = "FALL";
    $tutors_query = sprintf("SELECT Student.GTID, Student.Name, Student.Email
                    FROM Student, Tutors, Tutor_Time_Slot
                    WHERE Student.GTID = Tutors.GTID_Tutor
                    AND Tutor_Time_Slot.GTID = Tutors.GTID_Tutor
                    AND Tutors.School = '%s'
                    AND Tutors.Number = '%s'
                    AND Tutor_Time_Slot.Semester = '%s'
                    AND (",
                    mysql_real_escape_string($schoolName),
                    mysql_real_escape_string($courseNumber),
                    mysql_real_escape_string($currentSemester));

    for ($i = 0; $i < count($dayArray); $i++) {
      if ($i <= count($dayArray) - 2)
        $tutors_query = $tutors_query . sprintf("(Tutor_Time_Slot.WeekDay = '%s' AND " .
                                        "Tutor_Time_Slot.Time = '%s') OR\n",
                                        mysql_real_escape_string($dayArray[$i]),
                                        mysql_real_escape_string($timeArray[$i]));
      else
        $tutors_query = $tutors_query . sprintf("(Tutor_Time_Slot.WeekDay = '%s' AND " .
                                        "Tutor_Time_Slot.Time = '%s'));",
                                        mysql_real_escape_string($dayArray[$i]),
                                        mysql_real_escape_string($timeArray[$i]));
    }

    $result = $db->query($tutors_query);
    $retval = queryErrorHandler($db, $result);
    if (!$retval) {
      $message = 'Whole query: ' . $query;
      die($message);
    }

    $tutors = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $GTID = $row['gtid'];
      $name = $row['name'];
      $pos = strrpos($name, " ");
      $firstName = substr($name, 0, $pos);
      $lastName = substr($name, $pos);
      $email = $row['email'];
      array_push($tutors, array("GTID" => $GTID,
                                "FirstName" => $firstName,
                                "LastName" => $lastName,
                                "Email" => $email));
    }

    for ($i = 0; $i < count($tutors); $i++) {
      $GTID = $tutors[$i]['GTID'];
      $sql_prof_eval = sprintf("SELECT R.GTID_Tutor, AVG(R.Num_Evaluation) AS Avg_Prof_Rating,
                       COUNT(R.Num_Evaluation) AS Num_Professors
                       FROM Recommends R
                       WHERE R.GTID_Tutor = '%s' GROUP BY R.GTID_Tutor;",
                       mysql_real_escape_string($GTID));
      $result = $db->query($sql_prof_eval);
      $retval = queryErrorHandler($db, $result);
      if (!$retval) {
      	die('Failed query: ' . $sql_prof_eval);
      }
      while ($row = $result->fetch(PDO::FETCH_ASSOC))  {
        $tutors[$i] = array_merge($tutors[$i],
                                  array('Avg_Prof_Rating' => $row['avg_prof_rating'],
                                  'Num_Professors' => $row['num_professors']));
      }
    }

    for ($i = 0; $i < count($tutors); $i++) {
    	$GTID = $tutors[$i]['GTID'];
    	$sql_student_eval = sprintf("SELECT R.GTID_Tutor, AVG(R.Num_Evaluation) AS Avg_Student_Rating,
    							COUNT(R.Num_Evaluation) AS Num_Students
    							FROM Rates R
    							WHERE R.GTID_Tutor = '%s' GROUP BY R.GTID_Tutor;",
    							mysql_real_escape_string($GTID));
    	$result = $db->query($sql_student_eval);
    	$retval = queryErrorHandler($db, $result);
    	if (!$retval) {
    		die('Failed query: ' . $sql_student_eval);
    	}
    	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    		$tutors[$i] = array_merge($tutors[$i],
                      array('Avg_Student_Rating' => $row['avg_student_rating'],
                            'Num_Students' => $row['num_students']));
    	}
    }

    $prev_row = array(); $curr_row = array(); $formatted_tutors = array();
    for ($i = 0; $i < count($tutors); $i++) {
      $curr_row = $tutors[$i];
      if ($prev_row !== $curr_row) $formatted_tutors[$i] = $curr_row;
      $prev_row = $curr_row;
    }

    print_r($tutors);
    print("<br/><br/>Formatted:<br/>");
    print_r($formatted_tutors);
    return $formatted_tutors;
  }

  function getAvailableTutors($db, $schoolName, $courseNumber, $dayArray,
        $timeArray) {
      $currentSemester = "FALL";
      $query = sprintf("SELECT Student.Name, Student.Email, Student.GTID, " .
                       "Tutor_Time_Slot.Weekday, " .
                       "Tutor_Time_Slot.Time " .
                       "FROM Student, Recommends, Rates, Tutors, Tutor_Time_Slot " .
                       "WHERE Tutors.School = '%s' AND\n" .
                       "Tutors.Number = '%s' AND\n" .
                       "Tutor_Time_Slot.Semester = '%s'\nAND (",
                       mysql_real_escape_string($schoolName),
                       mysql_real_escape_string($courseNumber),
                       mysql_real_escape_string($currentSemester));
      for ($i = 0; $i < count($dayArray); $i++) {
        if ($i <= count($dayArray) - 2)
          $query = $query . sprintf("(Tutor_Time_Slot.WeekDay = '%s' AND " .
                                    "Tutor_Time_Slot.Time = '%s') OR\n",
                                    mysql_real_escape_string($dayArray[$i]),
                                    mysql_real_escape_string($timeArray[$i]));
        else
          $query = $query . sprintf("(Tutor_Time_Slot.WeekDay = '%s' AND " .
                                    "Tutor_Time_Slot.Time = '%s')) AND\n",
                                    mysql_real_escape_string($dayArray[$i]),
                                    mysql_real_escape_string($timeArray[$i]));
      }
      $query = $query . sprintf("Tutor_Time_Slot.GTID = Student.GTID AND\n" .
                                      "Recommends.GTID_Tutor = Student.GTID AND\n" .
                                      "Rates.GTID_Tutor = Student.GTID AND\n" .
                                      "Tutors.GTID_Tutor = Student.GTID\n" .
                                      "GROUP BY Student.Name, Student.Email, Student.GTID, Tutor_Time_Slot.Weekday, " .
                                        "Tutor_Time_Slot.Time\n" .
                                      "ORDER BY Student.Name, Tutor_Time_Slot.Weekday, " .
                                        "Tutor_Time_Slot.Time");
      return $query;
    }

  function executeQuery($db, $query) {
    print("<html><body>");
    print("<h1>DEBUGGING MENU</h1>");
    print("<p>Query:<br/>" . $query . "</p>");
    
    $result = $db->query($query);
    $retval = queryErrorHandler($db, $result);
    if (!$retval) {
      $message = 'Whole query: ' . $query;
      die($message);
    }
    return $result;
  }

  function formatTutorResult($result) {
      print("<p>Results:<br/>");
      $formattedResult = array();
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $name = $row["name"];
        $email = $row["email"];
        $day = $row["weekday"];
        $time = $row["time"];
        $tutor_gtid = $row["gtid"];

        $pos = strrpos($name, " ");
        $firstName = substr($name, 0, $pos);
        $lastName = substr($name, $pos);
        array_push($formattedResult, array("First" => $firstName,
                                           "Last" => $lastName,
                                           "Email" => $email,
                                           "Day" => $day,
                                           "Time" => $time,
                                           "GTID_Tutor" => $tutor_gtid));
        print(implode(", ", $row) . "<br/>");
      }
      print("</p></body></html>");
      return $formattedResult;
    }

  function redirectBack($formattedCourseResult, $formattedTutorResult, $school,
    $courseNumber) {
    $_SESSION["courseSearchResults"] = $formattedCourseResult;
    $_SESSION["tutorSearchResults"] = $formattedTutorResult;
    $_SESSION["school"] = $school;
    $_SESSION["courseNumber"] = $courseNumber;
    header("Location: ../views/tutor_search_view.php");
    die();
  }
?>