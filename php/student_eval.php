<?php
  session_start();

  $database = "4400_project_db";
  $con = mysql_connect(localhost, "root", "mysql");
  @mysql_select_db($database) or die("Unable to select database");

  $gtid = $_SESSION['user_gtid'];

  $query = sprintf("SELECT DISTINCT Hires.School, Hires.Number, Student.Name\n" .
                   "FROM Hires, Tutor, Student\n" .
                   "WHERE Hires.GTID_Undergraduate = '%s' AND " .
                   "Hires.GTID_Tutor = Student.GTID;",
                   mysql_real_escape_string($gtid));

  $result = mysql_query($query);
  if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
  }

  $formattedResult = array();
  while ($row = mysql_fetch_assoc($result)) {
    $school = $row["School"];
    $courseNumber = $row["Number"];
    $tutorName = $row["Name"];

    array_push($formattedResult, array("School" => $school,
                                       "CourseNumber" => $courseNumber,
                                       "TutorName" => $tutorName));
    print(implode(", ", $row) . "<br/>");
  }

  $_SESSION["courseRatingArray"] = $formattedResult;
  header("Location: ../html/student_eval.html");
  die();

?>