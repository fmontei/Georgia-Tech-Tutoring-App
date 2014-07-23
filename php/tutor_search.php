<?php
  session_start();
  $school = htmlspecialchars($_GET["schoolName"]);
  $courseNumber = htmlspecialchars($_GET["courseNumber"]);
  $preferredDays = htmlspecialchars($_GET["preferredDay"]);
  $preferredTimes = htmlspecialchars($_GET["preferredTime"]);

  $query  = explode('&', $_SERVER['QUERY_STRING']);
  $params = array();
  foreach($query as $param) {
    list($name, $value) = explode('=', $param);
    $params[urldecode($name)][] = urldecode($value);
  }

  $preferredDayArray = findDays($params);
  $preferredTimeArray = findTimes($params);
  $formattedTimeArray = reformatTimeArray($preferredTimeArray);

  $courseQuery = getAvailableStudentCourses($school, $courseNumber,
    $preferredDayArray, $formattedTimeArray);
  $tutorQuery = getAvailableTutors($school, $courseNumber, $preferredDayArray,
    $formattedTimeArray);

  $courseResult = executeQuery($courseQuery);
  $formattedCourseResult = formatCourseResult($courseResult);
  $tutorResult = executeQuery($tutorQuery);
  $formattedTutorResult = formatTutorResult($tutorResult);

  redirectBack($formattedCourseResult, $formattedTutorResult, $school,
    $courseNumber);

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

  function reformatTimeArray($timeArray) {
    $formatted = array();
    $formattedFirst = $formattedSecond = $formattedTime = "";
    foreach($timeArray as $array => $timeEntry) {
      $firstPos = strrpos($timeEntry, ":");
      $firstHalf = substr($timeEntry, 0, $firstPos);
      if ($firstHalf > 12) {
        $formattedFirst = $firstHalf - 12;
        $formattedSecond = "pm";
      } else {
        $formattedFirst = $firstHalf;
        $formattedSecond = "am";
      }
      $formattedTime = $formattedFirst . $formattedSecond;
      // Omit "0" from "09am", for example
      if (substr($formattedTime, 0, 1) === "0")
        $formattedTime = substr($formattedTime, 1);
      array_push($formatted, $formattedTime);
    }
    return $formatted;
  }

  function getAvailableStudentCourses($schoolName, $courseNumber, $dayArray,
      $timeArray) {
    $currentSemester = "FALL";
    $query = sprintf("SELECT DISTINCT Student.Name, Student.Email, " .
                     "AVG(DISTINCT Recommends.Num_Evaluation) AS Avg_Prof_Rating, " .
                     "COUNT(DISTINCT Recommends.Num_Evaluation) AS Num_Professors, " .
                     "AVG(DISTINCT Rates.Num_Evaluation) AS Avg_Student_Rating, " .
                     "COUNT(DISTINCT Rates.Num_Evaluation) AS Num_Students " .
                     "FROM Student, Recommends, Rates, Tutors, Tutor_Time_Slot " .
                     "WHERE Tutors.School = '%s' AND\n" .
                     "Tutors.Number = '%s' AND\n" .
                     "Tutor_Time_Slot.Semester = '%s'\nAND(",
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
                              "Student.GTID IN\n" .
                              "(SELECT DISTINCT Tutors.GTID_Tutor FROM Tutors)\n" .
                              "GROUP BY Student.Email\n" .
                              "ORDER BY Avg_Prof_Rating DESC;");
    return $query;
  }

  function getAvailableTutors($schoolName, $courseNumber, $dayArray,
        $timeArray) {
      $currentSemester = "FALL";
      $query = sprintf("SELECT Student.Name, Student.Email, Student.GTID, " .
                       "Tutor_Time_Slot.Weekday, " .
                       "Tutor_Time_Slot.Time " .
                       "FROM Student, Recommends, Rates, Tutors, Tutor_Time_Slot " .
                       "WHERE Tutors.School = '%s' AND\n" .
                       "Tutors.Number = '%s' AND\n" .
                       "Tutor_Time_Slot.Semester = '%s'\nAND(",
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
                                "Student.GTID IN\n" .
                                "(SELECT DISTINCT Tutors.GTID_Tutor FROM Tutors)\n" .
                                "GROUP BY Tutor_Time_Slot.Time\n" .
                                "ORDER BY Tutor_Time_Slot.Time ASC");
      return $query;
    }

  function executeQuery($query) {
    print("<html><body>");
    print("<h1>DEBUGGING MENU</h1>");
    print("<p>Query:<br/>" . $query . "</p>");
    $database = "4400_project_db";
    $con = mysql_connect(localhost, "root", "mysql");
    @mysql_select_db($database) or die("Unable to select database");
    $result = mysql_query($query);
    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
    }
    return $result;
  }

  function formatCourseResult($result) {
    print("<p>Results:<br/>");
    $formattedResult = array();
    while ($row = mysql_fetch_assoc($result)) {
      $name = $row["Name"];
      $email = $row["Email"];
      $profRating = $row["Avg_Prof_Rating"];
      $numProf = $row["Num_Professors"];
      $studentRating = $row["Avg_Student_Rating"];
      $numStudent = $row["Num_Students"];

      $pos = strrpos($name, " ");
      $firstName = substr($name, 0, $pos);
      $lastName = substr($name, $pos);
      array_push($formattedResult, array("First" => $firstName,
                                         "Last" => $lastName,
                                         "Email" => $email,
                                         "ProfRating" => $profRating,
                                         "NumProf" => $numProf,
                                         "StudentRating" => $studentRating,
                                         "NumStudent" => $numStudent));
      print(implode(", ", $row) . "<br/>");
    }
    print("</p></body></html>");
    return $formattedResult;
  }

  function formatTutorResult($result) {
      print("<p>Results:<br/>");
      $formattedResult = array();
      while ($row = mysql_fetch_assoc($result)) {
        $name = $row["Name"];
        $email = $row["Email"];
        $day = $row["Weekday"];
        $time = $row["Time"];
        $tutor_gtid = $row["GTID"];

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
    header("Location: ../html/tutor_search.html");
    die();
  }
?>