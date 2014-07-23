<?php
  session_start();

  if ($_SERVER["QUERY_STRING"] === "clear_report") {
    unset($_SESSION["admin_course_list"]);
    header("Location: ../html/menu.html");
    die();
  }

  $fall_checkbox = $spring_checkbox = $summer_checkbox = "";
  if (isset($_GET["fall_checkbox"])) {
    $fall_checkbox = $_GET["fall_checkbox"];
  }
  if (isset($_GET["spring_checkbox"])) {
    $spring_checkbox = $_GET["spring_checkbox"];
  }
  if (isset($_GET["summer_checkbox"])) {
    $summer_checkbox = $_GET["summer_checkbox"];
  }

  

  $query = "(SELECT Hires.School, Hires.Number, Hires.Semester, " .
            "COUNT(DISTINCT Hires.GTID_Undergraduate) As NumStudent, Count(DISTINCT Hires.GTID_Tutor) As NumTutor\n" .
            "from Hires NATURAL JOIN Course\n" .
            "where Hires.Semester = '" . $fall_checkbox . "'\n" .
            "GROUP BY Hires.School, Hires.Number)\n" .
            "UNION\n" .
            "(SELECT Hires.School, Hires.Number, Hires.Semester, " .
            "COUNT(DISTINCT Hires.GTID_Undergraduate) As NumStudent, Count(DISTINCT Hires.GTID_Tutor) As NumTutor\n" .
            "from Hires NATURAL JOIN Course\n" .
            "where Hires.Semester = '" . $spring_checkbox . "'\n" .
            "GROUP BY Hires.School, Hires.Number)\n" .
            "UNION\n" .
            "(SELECT Hires.School, Hires.Number, Hires.Semester, " .
            "COUNT(DISTINCT Hires.GTID_Undergraduate) As NumStudent, Count(DISTINCT Hires.GTID_Tutor) As NumTutor\n" .
            "from Hires NATURAL JOIN Course\n" .
            "where Hires.Semester = '" . $summer_checkbox . "'\n" .
            "GROUP BY Hires.School, Hires.Number)\n" .
            "ORDER BY School, Number, Semester;";

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
     print(implode(", ", $row) . "<br />");

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

  print("<br />");

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

  // Splice total rows into the final result array
  $finalResult = array();
  $row = array();
  $lastCourse = $formattedResult[0]["Course"];
  $i = $j = 0;
  while ($i < count($formattedResult)) {
    $row = $formattedResult[$i];
    if ($lastCourse != $row["Course"]) {
      $prev = $formattedResult[$i - 1];
      $finalResult[$j] =  array("Course" => "",
                                "Semester" => "Total",
                                "NumStudent" => $num_student_total[$prev ["Course"]],
                                "NumTutor" => $num_tutor_total[$prev["Course"]]);
      $j += 1;
      $finalResult[$j] = $row;
   } else {
     $finalResult[$j] = $row;
   }

    $lastCourse = $row["Course"];
    $i++; $j++;
  }

  // Splice FINAL row total into bottom of final result array
  array_push($finalResult, array("Course" => "",
                                "Semester" => "Total",
                                "NumStudent" => $num_student_total[$row["Course"]],
                                "NumTutor" => $num_tutor_total[$row["Course"]]));

  // Splice grand total into bottom of final result array
  array_push($finalResult, array("Course" => "",
                                     "Semester" => "Grand Total",
                                     "NumStudent" => $num_student_grand_total,
                                     "NumTutor" => $num_tutor_grand_total));

  foreach ($finalResult as $row) {
    print(implode(", ", $row) . "<br />");
  }

  $_SESSION["admin_course_list"] = $finalResult;
  header("Location: ../html/admin_course_list.html");
  die();
?>