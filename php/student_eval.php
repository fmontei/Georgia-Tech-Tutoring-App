<?php
  include 'globals.php';

  session_start();

  $db = dbConnect(); // From globals.php

  if (strpos($_SERVER["QUERY_STRING"], "populate_table") !== false) {
    populateTable($db);
  } else if (strpos($_SERVER["QUERY_STRING"], "submit_review") !== false) {
    processReview($db);
  }

  function populateTable($db) {
    $gtid = $_SESSION['user_gtid'];

    $query = sprintf("SELECT DISTINCT Hires.School, Hires.Number, Student.Name, " .
                     "Student.GTID\n" .
                     "FROM Hires, Tutor, Student\n" .
                     "WHERE Hires.GTID_Undergraduate = '%s' AND " .
                     "Hires.GTID_Tutor = Student.GTID;",
                     mysql_real_escape_string($gtid));

    $result = $db->query($query);
    $retval = queryErrorHandler($db, $result);
    if (!$retval) {
      $message = 'Invalid query: ' . $query;
      die($message);
    }

    $formattedResult = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $tutor_gtid = $row["gtid"];
      $school = $row["school"];
      $courseNumber = $row["number"];
      $tutorName = $row["name"];

      array_push($formattedResult, array("TutorGTID" => $tutor_gtid,
                                         "School" => $school,
                                         "CourseNumber" => $courseNumber,
                                         "TutorName" => $tutorName));
      print(implode(", ", $row) . "<br/>");
    }

    $_SESSION["courseRatingArray"] = $formattedResult;
    header("Location: ../views/student_eval_view.php");
    die();
  }

  function processReview($db) {
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
    $result = $db->query($query);
  	$retval = queryErrorHandler($db, $result);
    if (!$retval) {
      $message = 'Invalid query: ' . $query;
      die($message);
    } else {
      header("Location: ../views/menu_view.php");
      die();
    }
  }
?>