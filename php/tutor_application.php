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
  } else if (strpos($query_string, "submit_tutor_app") !== false) {
    parse_form();
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

  function parse_form() {
    $query = explode('&', $_SERVER['QUERY_STRING']);
    $params = array();
    foreach($query as $param) {
      list($name, $value) = explode('=', $param);
      $params[urldecode($name)][] = urldecode($value);
    }

    print("<h1>Printing list of form parameters:</h1><br/>");
    $gtid = $first_name = $last_name = $email = $phone = $gpa = $grad_status =
      $gta = "";
    foreach($params as $index => $param) {
      print($index . ": " . implode(", ", $param) . "<br />");
      if ($index === "gtid")             $gtid        = $param[0];
      else if ($index === "first_name")  $first_name  = $param[0];
      else if ($index === "last_name")   $last_name   = $param[0];
      else if ($index === "email")       $email       = $param[0];
      else if ($index === "phone")       $phone       = $param[0];
      else if ($index === "gpa")         $gpa         = $param[0];
      else if ($index === "grad_status") $grad_status = $param[0];
      else if ($index === "gta")         $gta         = $param[0];
    }

    $days = array(); $times = array();
    parseDayAndTime($params, $days, $times);
    $courses = array();
    parseCourses($params, $courses);
    print("Courses:<br/>");
    foreach ($courses as $course_entry) {
      print_r($course_entry);
      print("<br/>");
    }

    $semester = "FALL";
    insertTutorTimeSlotTable($gtid, $semester, $days, $times);
    insertTutorsTable($gtid, $courses);
    updateTutorTables($gtid, $first_name, $last_name, $email, $phone, $gpa);
  }

  function parseDayAndTime($params, &$days, &$times) {
    $day_times = array();
    foreach($params as $index => $param) {
      if (strpos($index, "day_time") !== FALSE) {
        $day_times = $param; break;
      }
    }
    foreach($day_times as $day_time) {
      $day_pos = strrpos($day_time, " ");
      $parsed_day = substr($day_time, 0, $day_pos);
      $parsed_time = substr($day_time, $day_pos);
      array_push($days, $parsed_day);
      array_push($times, $parsed_time);
    }
    print("<br />" . "Parsed days: " . implode(" ", $days) . "<br />");
    print("Parsed times: " . implode(" ", $times) . "<br />");
  }

  function parseCourses($params, &$courses) {
    $course_input = array();
    $course_indices = array();
    foreach($params as $index => $param) {
      if (strpos($index, "tutor_course_input") !== FALSE) {
        $course_input = $param; break;
      }
    }
    foreach($course_input as $course) {
      array_push($course_indices, $course);
    }

    $tutor_course_info = array();
    if (array_key_exists("tutor_course_info", $_SESSION)) {
      $tutor_course_info = $_SESSION["tutor_course_info"];
    }

    foreach ($course_indices as $index) {
      if ($index !== "")
        array_push($courses, $tutor_course_info[$index]);
    }

    header("Location: ../html/menu.html");
    die();
  }

  function updateTutorTables($gtid, $first_name, $last_name, $email, $phone, $gpa) {
    print("<h2>Tutor Queries</h2>");
    $tutor_query = sprintf("UPDATE Tutor\n" .
      "SET Phone = '%s', GPA = '%s'\n" .
      "WHERE GTID = '%s';",
      mysql_real_escape_string($phone),
      mysql_real_escape_string($gpa),
      mysql_real_escape_string($gtid));
    mysql_query($tutor_query);
    print("Update tutor query: " . $tutor_query . "<br/>");

    $student_query = sprintf("Update Student\n" .
      "SET Email = '%s', Name = '%s'\n" .
      "WHERE GTID = '%s';",
      mysql_real_escape_string($email),
      mysql_real_escape_string(trim($first_name . " " . $last_name)),
      mysql_real_escape_string($gtid));
    mysql_query($student_query);
    print("Update tutor query: " . $student_query);
  }

  function insertTutorsTable($gtid, $courses) {
    print("<h2>Tutors Slot Queries</h2>");
    $i = 0;
    foreach($courses as $course) {
      $query = sprintf("INSERT INTO Tutors(GTID_Tutor, School, Number, GTA)\n" .
        "VALUES('%s', '%s', '%s', '%s')",
        mysql_real_escape_string($gtid),
        mysql_real_escape_string($course["School"]),
        mysql_real_escape_string($course["Number"]),
        mysql_real_escape_string($course["GTA"]));
        mysql_query($query);
        print("Query " . $i++ . ": " . $query . "<br/>");
    }
  }

  function insertTutorTimeSlotTable($gtid, $semester, $dayArray, $timeArray) {
    print("<h2>Tutor Time Slot Queries</h2>");
    for ($i = 0; $i < count($dayArray); $i++) {
      $query = sprintf("INSERT INTO Tutor_Time_Slot(GTID, Time, Semester, Weekday)\n" .
        "VALUES('%s', '%s', '%s', '%s')",
        mysql_real_escape_string($gtid),
        mysql_real_escape_string($semester),
        mysql_real_escape_string($dayArray[$i]),
        mysql_real_escape_string($timeArray[$i]));
      mysql_query($query);
      print("Query " . $i . ": " . $query . "<br/>");
    }
  }
?>