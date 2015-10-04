<?php
  include 'globals.php';

  session_start();

  $db = dbConnect();

  $user_gtid = trim($_SESSION['user_gtid']);
  $tutor_gtid = trim($_GET["tutorGTIDSelection"]);
  $school = trim($_SESSION["school"]);
  $number = trim($_SESSION["courseNumber"]);
  $time = trim($_GET["tutorTimeSelection"]);
  $semester = "FALL";
  $weekday = trim($_GET["tutorDaySelection"]);

  checkForRedundantTime($db, $school, $number, $user_gtid, $tutor_gtid, $time, $semester, $weekday);

  $query = sprintf ("INSERT INTO Hires(GTID_Undergraduate, GTID_Tutor, " .
                     "School, Number, Time, Semester, Weekday) " .
                     "VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s');",
                     mysql_real_escape_string($user_gtid),
                     mysql_real_escape_string($tutor_gtid),
                     mysql_real_escape_string($school),
                     mysql_real_escape_string($number),
                     mysql_real_escape_string($time),
                     mysql_real_escape_string($semester),
                     mysql_real_escape_string($weekday));

  $result = $db->query($query);
  $retval = queryErrorHandler($db, $result);
  if (!$retval) {
    $message = 'Invalid query: ' . $query . "<br/>";
    die($message);
  } else {
    unset($_SESSION['school']);
    unset($_SESSION['courseNumber']);
    unset($_SESSION['courseSearchResults']);
    unset($_SESSION['tutorSearchResults']);
    unset($_SESSION["redundant_time_error"]);
    header("Location: ../views/menu_view.php");
    die();
  }

  function checkForRedundantTime($db, $school, $number, $user_gtid, $tutor_gtid, $time, $semester,
    $weekday) {
    $query = sprintf("SELECT School, Number, Time, Semester, Weekday FROM HIRES\n" .
                     "WHERE GTID_Undergraduate = '%s' AND GTID_Tutor = '%s'",
                     mysql_real_escape_string($user_gtid),
					 					 mysql_real_escape_string($tutor_gtid));
					 
    $result = $db->query($query);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $row_school = $row["school"];
      $row_number = $row["number"];
      $row_time = $row["time"];
      $row_semester = $row["semester"];
      $row_weekday = $row["weekday"];
      if ($time === $row_time and $semester === $row_semester and
          $weekday === $row_weekday) {
        displayRepeatedTimeSlotError($time, $semester, $weekday);
        return;
      } else if ($school === $row_school and $number === $row_number and
          $semester === $row_semester) {
        displayRepeatedCourseError($school, $number, $semester);
        return;
      }
    }

    $next_query = ("SELECT * FROM HIRES;");
    $next_result = $db->query($next_query);
    while ($row = $next_result->fetch(PDO::FETCH_ASSOC)) {
      print(implode(", ", $row) . "<br/>");
      $row_school = $row["school"];
      $row_number = $row["number"];
      $row_time = $row["time"];
      $row_semester = $row["semester"];
      $row_weekday = $row["weekday"];
	  $row_tutor_gtid = $row["gtid_tutor"];
      if ($school === $row_school and $number === $row_number and
          $semester === $row_semester and $time == $row_time and
          $weekday === $row_weekday and $row_tutor_gtid === $tutor_gtid) {
        displayRepeatedUndergradError($school, $number, $semester, $weekday,
          $time);
        return;
      }
    }
  }

  function displayRepeatedTimeSlotError($time, $semester, $weekday) {
    $_SESSION["redundant_time_error"] = "Error: you have are already signed up " .
      "the selected time slot: " . $semester . " " . $weekday . " " . $time . ".";
    unset($_SESSION['school']);
    unset($_SESSION['courseNumber']);
    unset($_SESSION['courseSearchResults']);
    unset($_SESSION['tutorSearchResults']);
    header("Location: ../views/tutor_search_view.php");
    die();
  }

  function displayRepeatedCourseError($school, $number, $semester) {
    $_SESSION["redundant_time_error"] = "Error: you already receive tutoring " .
      "in the following course " . $school . " " . $number . " during the " .
    $semester . " semester.";
    unset($_SESSION['school']);
    unset($_SESSION['courseNumber']);
    unset($_SESSION['courseSearchResults']);
    unset($_SESSION['tutorSearchResults']);
    header("Location: ../views/tutor_search_view.php");
    die();
  }

  function displayRepeatedUndergradError($school, $number, $semester, $weekday,
    $time) {
    $_SESSION["redundant_time_error"] = "Error: another student has already registered" .
      " for the following course: " . $school . " " . $number . " during the " .
      " the following time: " . $semester . " " . $weekday . " " . $time . ".";
    $semester . " semester.";
    unset($_SESSION['school']);
    unset($_SESSION['courseNumber']);
    unset($_SESSION['courseSearchResults']);
    unset($_SESSION['tutorSearchResults']);
    header("Location: ../views/tutor_search_view.php");
    die();
  }
?>
