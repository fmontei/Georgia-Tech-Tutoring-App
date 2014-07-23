<?php
	session_start();

  $database = "4400_project_db";
  $con = mysql_connect("localhost", "root", "mysql");
  @mysql_select_db($database) or die("Unable to select database");

  $query_string = $_SERVER["QUERY_STRING"];
  if (strpos($query_string, "populate_form") !== false) {
    $tutor_gtid = $grad_type = "";
    if (isset($_SESSION["user_gtid"])) {
      $tutor_gtid = $_SESSION["user_gtid"];
    }
    if (isset($_SESSION["gradType"])) {
      $grad_type = $_SESSION["gradType"];
    }
    populate_form($tutor_gtid, $grad_type);
  } else if (strpos($query_string, "clear_form") !== false) {
    clear_form();
  }

  function populate_form($tutor_gtid, $grad_type) {
    $tutor_query = sprintf("SELECT DISTINCT Student.GTID, Email, Name, Phone, GPA, GTA\n" .
                    "FROM Student, Tutor, Tutors\n" .
                    "WHERE Student.GTID = '%s' AND\n" .
                    "Student.GTID = Tutor.GTID AND\n" .
                    "Student.GTID = Tutors.GTID_Tutor;",
                     mysql_real_escape_string($tutor_gtid));

    $course_query = "SELECT * From Tutors";

    print("Query:<br/>" . $tutor_query . "<br/>");
    $tutor_result = mysql_query($tutor_query);
    if (!$tutor_result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole Tutor Query: ' . $tutor_query;
      die($message);
    }

    $tutor_app_info;
    while($row = mysql_fetch_assoc($tutor_result)) {
      $gtid = trim($row["GTID"]);
      $email = trim($row["Email"]);
      $name = trim($row["Name"]);
      $pos = strrpos($name, " ");
      $first_name = trim(substr($name, 0, $pos));
      $last_name = trim(substr($name, $pos));
      $phone = trim($row["Phone"]);
      $gpa = trim($row["GPA"]);
      $gta = trim($row["GTA"]);

      $tutor_app_info = array("GTID" => $gtid, "Email" => $email,
        "FirstName" => $first_name, "LastName" => $last_name, "Phone" => $phone,
        "GPA" => $gpa, "GTA" => $gta, "GradType" => $grad_type);
      break;
    }
    print("<br />Tutor Array:<br />" . implode(", ", $row) . "<br />");

    print("<br/>Course Array:<br />");
    $course_result = mysql_query($course_query);
    $tutor_course_info = array();
    while($row = mysql_fetch_assoc($course_result)) {
      if (trim($row["GTID_Tutor"]) === $tutor_gtid) {
        $count = 0;
        foreach ($tutor_course_info as $prev_row) {
          if ($prev_row["School"] === $row["School"]
            and $prev_row["Number"] === $row["Number"]) {
            $count++;
          }
        }
        if ($count === 0)
          array_push($tutor_course_info, array("School" => $row["School"],
                                                       "Number" => $row["Number"],
                                                       "GTA" => $row["GTA"]));
      } else {
        $count = 0;
        foreach ($tutor_course_info as $prev_row) {
          if ($prev_row["School"] === $row["School"]
            and $prev_row["Number"] === $row["Number"]) {
            $count++;
          }
        }
        if ($count === 0)
          array_push($tutor_course_info, array("School" => $row["School"],
                                                       "Number" => $row["Number"],
                                                       "GTA" => "0"));
      }
    }

    foreach ($tutor_course_info as $row) {
      print(implode(", ", $row) . "<br />");
    }

    $_SESSION["tutor_app_info"] = $tutor_app_info;
    $_SESSION["tutor_course_info"] = $tutor_course_info;
    header("Location: ../html/application.html");
    die();
  }

  function insert_row($tutor_course_info, $row) {
    foreach ($tutor_course_info as $prev_row) {
      print("curr: " . $row["School"] . " prev: " . $prev_row["School"] . "<br/>");
      if ($prev_row["School"] === $row["School"]) return;
    }
    array_push($tutor_course_info, array("School" => $row["School"],
                                                 "Number" => $row["Number"],
                                                 "GTA" => "0"));
  }

  function clear_form() {
    unset($_SESSION["tutor_app_info"]);
    header("Location: ../html/menu.html");
    die();
  }
?>