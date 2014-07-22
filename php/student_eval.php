<?php
  session_start();

  $database = "4400_project_db";
  $con = mysql_connect("localhost", "root", "mysql");
  @mysql_select_db($database) or die("Unable to select database");

  if (strpos($_SERVER["QUERY_STRING"], "populate_table") !== false) {
    populateTable();
  } else if (strpos($_SERVER["QUERY_STRING"], "submit_review") !== false) {
    processReview();
  }

  function populateTable() {
    $gtid = $_SESSION['user_gtid'];

    $query = sprintf("SELECT DISTINCT Hires.School, Hires.Number, Student.Name, " .
                     "Student.GTID\n" .
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
      $tutor_gtid = $row["GTID"];
      $school = $row["School"];
      $courseNumber = $row["Number"];
      $tutorName = $row["Name"];

      array_push($formattedResult, array("TutorGTID" => $tutor_gtid,
                                         "School" => $school,
                                         "CourseNumber" => $courseNumber,
                                         "TutorName" => $tutorName));
      print(implode(", ", $row) . "<br/>");
    }

    $_SESSION["courseRatingArray"] = $formattedResult;
    header("Location: ../html/student_eval.html");
    die();
  }

  function processReview() {
    $user_gtid = $_SESSION['user_gtid'];
    $tutor_gtid = $_GET["tutorGTIDSelection"];
    $tutor_name = $_GET["tutorNameSelection"];
    $school = $_GET["tutorSchoolSelection"];
    $courseNum = $_GET["tutorCourseSelection"];
    $desc_eval = $_GET["desc_eval"];
    $num_eval = $_GET["final_num_eval_input"];

    $query = sprintf("INSERT INTO Rates(GTID_Undergraduate, GTID_Tutor, School, " .
      "Number, Num_Evaluation, Desc_Evaluation) VALUES('%s', '%s', '%s', '%s', '%s', '%s');",
      mysql_real_escape_string($user_gtid), mysql_real_escape_string($tutor_gtid),
      mysql_real_escape_string($school), mysql_real_escape_string($courseNum),
      mysql_real_escape_string($num_eval), mysql_real_escape_string($desc_eval));
    $result = mysql_query($query);
    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
    } else {
      header("Location: ../html/menu.html");
      die();
    }
  }
?>