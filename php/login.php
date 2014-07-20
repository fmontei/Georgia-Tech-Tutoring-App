<?php
  session_start();
  // Gather login credentials
  $gtid = htmlspecialchars($_POST["user_gtid"]);
  $password = htmlspecialchars($_POST["password"]);

  // Create connection
  $database = "4400_project_db";
  $con = mysql_connect(localhost, "root", "mysql");
  @mysql_select_db($database) or die( "Unable to select database");

  $count = getProfessorByGTID($gtid, $password);
  if ($count == 0) $count = getAdministratorByGTID($gtid, $password);
  if ($count == 0) $count = getTutorByGTID($gtid, $password);
  if ($count == 0) $count = getStudentByGTID($gtid, $password);
  if ($count == 0) createLoginErrorMsg();
  else redirectToMenu();

  function getProfessorByGTID($gtid, $password) {
    $query = sprintf("SELECT GTID, Password FROM Professor AS P " .
                     "WHERE P.GTID = '%s' AND P.Password = '%s'",
                     mysql_real_escape_string($gtid),
                     mysql_real_escape_string($password));

    $result = mysql_query($query);

    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
    }

    $rowCount = 0;
    while ($row = mysql_fetch_assoc($result)) {
      $rowCount = $rowCount + 1;
    }

    if ($rowCount != 0) $_SESSION['userType'] = 'professor';
    return $rowCount;
  }

  function getAdministratorByGTID($gtid, $password) {
    $query = sprintf("SELECT GTID, Password FROM Administrator AS A " .
                     "WHERE A.GTID = '%s' AND A.Password = '%s'",
                     mysql_real_escape_string($gtid),
                     mysql_real_escape_string($password));

    $result = mysql_query($query);

    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
    }

    $rowCount = 0;
    while ($row = mysql_fetch_assoc($result)) {
      $rowCount = $rowCount + 1;
    }

    if ($rowCount != 0) $_SESSION['userType'] = 'admin';
    return $rowCount;
  }

  function getTutorByGTID($gtid, $password) {
    $query = sprintf("SELECT GTID, Password FROM Tutor AS T " .
                     "WHERE T.GTID = '%s' AND T.Password = '%s'",
                     mysql_real_escape_string($gtid),
                     mysql_real_escape_string($password));

    $result = mysql_query($query);

    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
    }

    $rowCount = 0;
    while ($row = mysql_fetch_assoc($result)) {
      $rowCount = $rowCount + 1;
    }

    if ($rowCount != 0) $_SESSION['userType'] = 'tutor';
    return $rowCount;
  }

  function getStudentByGTID($gtid, $password) {
    $query = sprintf("SELECT GTID, Password FROM Student AS S " .
                     "WHERE S.GTID = '%s' AND S.Password = '%s'",
                     mysql_real_escape_string($gtid),
                     mysql_real_escape_string($password));

    $result = mysql_query($query);

    if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
    }

    $rowCount = 0;
    while ($row = mysql_fetch_assoc($result)) {
      $rowCount = $rowCount + 1;
    }

    if ($rowCount != 0) $_SESSION['userType'] = 'student';
    return $rowCount;
  }

  function createLoginErrorMsg() {
    $_SESSION['loginError'] = "User does not exist or authentication" .
                              " failed. Please try again.";
    header("Location: ../html/login.html");
    die();
  }

  function redirectToMenu() {
    unset($_SESSION['loginError']);
    header("Location: ../html/menu.html");
    die();
  }
?>