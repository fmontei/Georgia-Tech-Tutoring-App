<?php
  include 'globals.php';

  session_start();
  // Gather login credentials
  $gtid = htmlspecialchars($_POST['user_gtid']);
  $password = htmlspecialchars($_POST['password']);

  $db = dbConnect();

  $name_query = sprintf("SELECT Student.Name FROM Student\n" .
                        "WHERE Student.GTID = '%s'", $gtid);
  $name_result = $db->query($name_query);
  queryErrorHandler($db, $name_result);
  $name = "";
  while ($row = $name_result->fetch(PDO::FETCH_ASSOC)) {
    $name = $row["name"];
    break;
  }

  $count = getProfessorByGTID($db, $gtid, $password);
  if ($count == 0) $count = getAdministratorByGTID($db, $gtid, $password);
  if ($count == 0) $count = getTutorByGTID($db, $gtid, $password);
  if ($count == 0) $count = getUndergradByGTID($db, $gtid, $password);
  if ($count == 0) $count = getGradByGTID($db, $gtid, $password);
  if ($count == 0) createLoginErrorMsg();
  else redirectToMenu($gtid, $name);

  function getProfessorByGTID($db, $gtid, $password) {
    $query = sprintf("SELECT GTID, Password FROM Professor AS P " .
                     "WHERE P.GTID = '%s' AND P.Password = '%s'",
                     $gtid, $password);

    $result = $db->query($query);
    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      queryErrorHandler($db, $name_result);
      die($message);
    }

    $rowCount = 0;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $rowCount = $rowCount + 1;
    }

    if ($rowCount != 0) $_SESSION['userType'] = 'professor';
    return $rowCount;
  }

  function getAdministratorByGTID($db, $gtid, $password) {
    $query = sprintf("SELECT GTID, Password FROM Administrator AS A " .
                     "WHERE A.GTID = '%s' AND A.Password = '%s'",
                     $gtid, $password);

    $result = $db->query($query);
    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      queryErrorHandler($db, $name_result);
      die($message);
    }

    $rowCount = 0;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $rowCount = $rowCount + 1;
    }

    if ($rowCount != 0) $_SESSION['userType'] = 'admin';
    return $rowCount;
  }

  function getTutorByGTID($db, $gtid, $password) {
    $query = sprintf("SELECT GTID, Password FROM Tutor AS T " .
                     "WHERE T.GTID = '%s' AND T.Password = '%s'",
                     $gtid, $password);

    $result = $db->query($query);
    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      queryErrorHandler($db, $name_result);
      die($message);
    }

    $rowCount = 0;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $rowCount = $rowCount + 1;
    }

    if ($rowCount != 0) {
      $_SESSION['userType'] = 'tutor';
      $count = getUndergradByGTID($db, $gtid, $password);
      if ($count == 0) getGradByGTID($db, $gtid, $password);
    }

    return $rowCount;
  }

  function getUndergradByGTID($db, $gtid, $password) {
    $query = sprintf("SELECT GTID, Password\n" .
                      "FROM Undergraduate AS U\n" .
                      "WHERE U.GTID = '%s' AND U.Password = '%s';",
                      $gtid, $password);

    $result = $db->query($query);
    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      queryErrorHandler($db, $name_result);
      die($message);
    }

    $rowCount = 0;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $rowCount = $rowCount + 1;
    }

    if ($rowCount != 0) $_SESSION['gradType'] = 'undergrad';
    return $rowCount;
  }

  function getGradByGTID($db, $gtid, $password) {
    $query = sprintf("SELECT GTID, Password\n" .
                     "FROM Graduate AS G\n" .
                     "WHERE G.GTID = '%s' AND G.Password = '%s';",
                     $gtid, $password);

    $result = $db->query($query);
    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      queryErrorHandler($db, $name_result);
      die($message);
    }

    $rowCount = 0;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $rowCount = $rowCount + 1;
    }

    if ($rowCount != 0) $_SESSION['gradType'] = 'grad';
    return $rowCount;
  }

  function createLoginErrorMsg() {
    $_SESSION['loginError'] = "User does not exist or authentication" .
                              " failed. Please try again.";
    header("Location: ../views/login_view.php");
    die();
  }

  function redirectToMenu($gtid, $name) {
    unset($_SESSION['loginError']);
    $_SESSION['user_gtid'] = $gtid;
    $_SESSION['user_name'] = $name;
    header("Location: ../views/menu_view.php");
    die();
  }
?>