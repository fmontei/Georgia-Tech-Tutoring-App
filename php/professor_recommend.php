<?php
	session_start();
	
	$database = "4400_project_db";
	$con = mysql_connect("localhost", "root", "mysql");
	@mysql_select_db($database) or die("Unable to select database");

  if (strpos($_SERVER["QUERY_STRING"], "clear_recommendation") !== false) {
    clear_recommendation();
    return;
  }

  $user_gtid = $_SESSION['user_gtid'];
  $tutor_gtid = trim($_GET["tutorGTIDSelection"]);
  $desc_eval = trim($_GET["desc_eval"]);
  $num_eval = trim($_GET["final_num_eval_input"]);

  print("<h1>Debugging for Professor Recommend</h1>");
  print("<ul><li>user_gtid: " . $user_gtid . "</li>" .
        "<li>tutor_gtid: " . $tutor_gtid . "</li>" .
        "<li>desc_eval: " . $desc_eval . "</li>" .
        "<li>user_gtid: " . $num_eval . "</li></ul>");

  //check if valid GTID
  $query = sprintf("SELECT Tutor.GTID \n" .
          "FROM Tutor \n" .
          "WHERE Tutor.GTID = '%s';",
          mysql_real_escape_string($tutor_gtid));

  $result = mysql_query($query);
  $count = 0;
  while ($row = mysql_fetch_assoc($result)) $count++;
  if ($count === 0) {
    $_SESSION["duplicate_recommendation_error"] =
          "Error: Invalid GTID provided.";
    header("Location: ../html/professor_eval.html");
    die();
  }

  $query = sprintf("INSERT INTO Recommends(GTID_Tutor, GTID_Professor,\n" .
      "Num_Evaluation, Desc_Evaluation)\n" .
      "VALUES('%s', '%s', '%s', '%s');",
      mysql_real_escape_string($tutor_gtid),
      mysql_real_escape_string($user_gtid),
      mysql_real_escape_string($num_eval),
      mysql_real_escape_string($desc_eval));
  $result = mysql_query($query);

  if (!$result) {
    $_SESSION["duplicate_recommendation_error"] =
      "Error: You cannot submit another recommendation for the same student.";
    header("Location: ../html/professor_eval.html");
    die();
  }

  clear_recommendation();

  function clear_recommendation() {
    unset($_SESSION["duplicate_recommendation_error"]);
    header("Location: ../html/menu.html");
    die();
  }
?>
