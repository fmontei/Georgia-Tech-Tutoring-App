<?php
	session_start();

  $database = "4400_project_db";
  $con = mysql_connect("localhost", "root", "mysql");
  @mysql_select_db($database) or die("Unable to select database");

  $query_string = $_SERVER["QUERY_STRING"];
  if (strpos($query_string, "populate_form") !== false) {
    $tutor_gtid = "";
    if (isset($_SESSION["user_gtid"])) {
      $tutor_gtid = $_SESSION["user_gtid"];
    }
    populate_form($tutor_gtid);
  } else if (strpos($query_string, "clear_form") !== false) {
    clear_form();
  }

  function populate_form($tutor_gtid) {
    $query = sprintf("SELECT DISTINCT Student.GTID, Email, Name, Phone, GPA, GTA\n" .
                    "FROM Student, Tutor, Tutors\n" .
                    "WHERE Student.GTID = '%s' AND\n" .
                    "Student.GTID = Tutor.GTID AND\n" .
                    "Student.GTID = Tutors.GTID_Tutor;",
                     mysql_real_escape_string($tutor_gtid));

    print("Query:<br/>" . $query . "<br/>");
    $result = mysql_query($query);
    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
    }

    $tutor_app_info;
    while($row = mysql_fetch_assoc($result)) {
      $gtid = trim($row["GTID"]);
      $email = trim($row["Email"]);
      $name = trim($row["Name"]);
      $pos = strrpos($name, " ");
      $first_name = trim(substr($name, 0, $pos));
      $last_name = trim(substr($name, $pos));
      $phone = trim($row["Phone"]);
      $gpa = trim($row["GPA"]);
      $gta = trim($row["GTA"]);
      $gta = ($gta == "1") ? "Graduate" : "Undergraduate";

      $tutor_app_info = array("GTID" => $gtid, "Email" => $email,
        "FirstName" => $first_name, "LastName" => $last_name, "Phone" => $phone,
        "GPA" => $gpa, "GTA" => $gta);
      break;
    }
    print("<br />Tutor Array:<br />" . implode(", ", $row) . "<br />");

    $_SESSION["tutor_app_info"] = $tutor_app_info;
    header("Location: ../html/application.html");
    die();
  }

  function clear_form() {
    unset($_SESSION["tutor_app_info"]);
    header("Location: ../html/menu.html");
    die();
  }
?>