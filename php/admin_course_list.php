<?php
  session_start();

  $query = sprintf("(SELECT Hires.School, Hires.Number, Hires.Semester, " .
                    "COUNT(DISTINCT Hires.GTID_Undergraduate) As NumStudent, Count(DISTINCT Hires.GTID_Tutor) As NumTutor\n" .
                    "from Hires NATURAL JOIN Course\n" .
                    "where Hires.Semester = 'FALL'\n" .
                    "GROUP BY Hires.School, Hires.Number)\n" .
                    "UNION\n" .
                    "(SELECT Hires.School, Hires.Number, Hires.Semester, " .
                    "COUNT(DISTINCT Hires.GTID_Undergraduate) As NumStudent, Count(DISTINCT Hires.GTID_Tutor) As NumTutor\n" .
                    "from Hires NATURAL JOIN Course\n" .
                    "where Hires.Semester = 'SPRING'\n" .
                    "GROUP BY Hires.School, Hires.Number)");


  print("<html><body>");
  print("<h1>Summary Report Debugging Menu</h1>");
  print("<p>Query:<br/>" . $query . "</p>");

  $database = "4400_project_db";
  $con = mysql_connect("localhost", "root", "mysql");
  @mysql_select_db($database) or die("Unable to select database");

  $result = mysql_query($query);
  if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
  }

  $formattedResult = array();
  $num_student_grand_total = $num_tutor_grand_total = 0;
  $num_student_total = array();
  $num_tutor_total = array();
  while ($row = mysql_fetch_assoc($result)) {
    $school = $row["School"];
    $number = $row["Number"];
    $course = $school . " " . $number;
    $semester = $row["Semester"];
    $num_student = $row["NumStudent"];
    $num_tutor = $row["NumTutor"];

    $formattedRow = array("Course" => $course,
                         "Semester" => $semester,
                         "NumStudent" => $num_student,
                         "NumTutor" => $num_tutor);
    array_push($formattedResult, $formattedRow);

    calculateStudentTotalPerCourse($num_student_total, $course, $num_student);
    calculateTutorTotalPerCourse($num_tutor_total, $course, $num_tutor);
    $num_student_grand_total += $num_student;
    $num_tutor_grand_total += $num_tutor;
  }

  function calculateStudentTotalPerCourse(&$num_student_total, $course, $num_student) {
    if (!isset($num_student_total[$course])) {
      $num_student_total[$course] = $num_student;
    } else {
      $num_student_total[$course] = $num_student_total[$course] + $num_student;
    }
  }

  function calculateTutorTotalPerCourse(&$num_tutor_total, $course, $num_tutor) {
    if (!isset($num_tutor_total[$course])) {
      $num_tutor_total[$course] = $num_tutor;
    } else {
      $num_tutor_total[$course] = $num_tutor_total[$course] + $num_tutor;
    }
  }

  // Splice total rows into the formatted result array
  /*$lastRow = $formattedResult[0];
  $count = 0;
  foreach ($formattedResult as $row) {
    if ($lastRow != $row) {

      $formattedResult[$count] =  array("Course" => "",
                                        "Semester" => "Total",
                                        "NumStudent" => $num_student_total[$row["Course"]],
                                        "NumTutor" => $num_tutor_total[$row["Course"]]);
     }
    $lastRow = $row;
    $count++;
  }*/

  // Splice grand totals into bottom of formatted result array
  array_push($formattedResult, array("Course" => "",
                                     "Semester" => "Grand Total",
                                     "NumStudent" => $num_student_grand_total,
                                     "NumTutor" => $num_tutor_grand_total));

  foreach ($formattedResult as $row) {
      print(implode(", ", $row) . "<br />");
  }

  $_SESSION["admin_course_list"] = $formattedResult;
  header("Location: ../html/admin_course_list.html");
  die();
?>