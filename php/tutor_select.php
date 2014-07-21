<?php
  session_start();
  $user_gtid = trim($_SESSION['user_gtid']);
  $tutor_gtid = trim($_GET["tutorGTIDSelection"]);
  $school = trim($_SESSION["school"]);
  $number = trim($_SESSION["courseNumber"]);
  $time = trim($_GET["tutorTimeSelection"]);
  $semester = "FALL";
  $weekday = trim($_GET["tutorDaySelection"]);

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

  $database = "4400_project_db";
  $con = mysql_connect(localhost, "root", "mysql");
  @mysql_select_db($database) or die("Unable to select database");
  $result = mysql_query($query);

  if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "<br/>";
    $message .= 'Whole query: ' . $query . "<br/>";
    die($message);
  } else {
    unset($_SESSION['school']);
    unset($_SESSION['courseNumber']);
    unset($_SESSION['courseSearchResults']);
    unset($_SESSION['tutorSearchResults']);
    header("Location: ../html/menu.html");
  }
?>