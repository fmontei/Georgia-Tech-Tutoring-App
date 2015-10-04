<?php
  include 'globals.php';

	session_start();
	
	$db = dbConnect();

  $user_gtid = $_SESSION['user_gtid'];

  // Clear recommendation if user clicks 'Cancel'
  if (strpos($_SERVER['QUERY_STRING'], 'clear_recommendation') !== false) {
    clear_recommendation();
    return;
  } else if (strpos($_SERVER['QUERY_STRING'], 'fetch_tutors') !== false) {
    $result = fetchAllTutorIDs($db);
    storeAllTutorIDs($result);
    header('Location: ../views/professor_eval_view.php');
    die();
  } else if ($_GET['tutorID'] !== null) {
    get_prev_recommendation($db, $_SESSION['user_gtid'], trim($_GET['tutorID']));
    die();
  } else {
    create_new_recommendation($db, $user_gtid);
  }

  function fetchAllTutorIDs($db) {
    $query = sprintf('SELECT GTID FROM Tutor');
    $result = $db->query($query);
    return $result;
  }

  function storeAllTutorIDs($tutorIDs) {
    $availableTutorIDs = [];
    while ($row = $tutorIDs->fetch(PDO::FETCH_ASSOC))  {
      array_push($availableTutorIDs, $row['gtid']);
    }
    $_SESSION['availableTutorIDs'] = $availableTutorIDs;  
  }

  // Recommendation already exists
  function get_prev_recommendation($db, $user_gtid, $tutor_gtid) {
    $prev_recommendation_query = sprintf('SELECT Num_Evaluation, Desc_Evaluation ' .
      'FROM Recommends ' .
      'WHERE GTID_TUTOR = "%s" AND ' .
      'GTID_Professor = "%s";',
      mysql_real_escape_string($tutor_gtid),
      mysql_real_escape_string($user_gtid));
    $prev_recommendation_result = $db->query($prev_recommendation_query);
    $prev_recommendation = new stdClass();
    while ($row = $prev_recommendation_result->fetch(PDO::FETCH_ASSOC))  {
      $prev_recommendation->num_evaluation = $row['num_evaluation'];
      $prev_recommendation->desc_evaluation = $row['desc_evaluation'];
      break;
    }
    $_SESSION['prev_recommendation_exists'] = true;
    echo json_encode($prev_recommendation);
  }

  function create_new_recommendation($db, $user_gtid) {
    $tutor_gtid = trim($_GET["tutorGTIDSelection"]);
    $desc_eval = trim($_GET["desc_eval"]);
    $num_eval = trim($_GET["final_num_eval_input"]);
    
    $query = sprintf("SELECT Tutor.GTID \n" .
            "FROM Tutor \n" .
            "WHERE Tutor.GTID = '%s';",
            mysql_real_escape_string($tutor_gtid));

    $result = $db->query($query);
    $count = 0;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) $count++;
    if ($count === 0) {
      $_SESSION["recommendation_error"] = "Error: Invalid GTID provided.";
      header("Location: ../views/professor_eval_view.php");
      die();
    }
    
    if ($_SESSION['prev_recommendation_exists']) {
      $query = sprintf("UPDATE Recommends\n" .
                       "SET Num_Evaluation = '%s', Desc_Evaluation = '%s'\n" .
                       "WHERE GTID_Tutor = '%s' AND GTID_Professor = '%s';",
          mysql_real_escape_string($num_eval),
          mysql_real_escape_string($desc_eval),
          mysql_real_escape_string($tutor_gtid),
          mysql_real_escape_string($user_gtid));
      $result = $db->query($query);
    } else {
      $query = sprintf("INSERT INTO Recommends(GTID_Tutor, GTID_Professor,\n" .
          "Num_Evaluation, Desc_Evaluation)\n" .
          "VALUES('%s', '%s', '%s', '%s');",
          mysql_real_escape_string($tutor_gtid),
          mysql_real_escape_string($user_gtid),
          mysql_real_escape_string($num_eval),
          mysql_real_escape_string($desc_eval));
      $result = $db->query($query);
    }
    
    unset($_SESSION["recommendation_error"]);
    header("Location: ../views/professor_eval_view.php");
    die();
  }

  function clear_recommendation() {
    unset($_SESSION["recommendation_error"]);
    header("Location: ../views/menu_view.php");
    die();
  }
?>
