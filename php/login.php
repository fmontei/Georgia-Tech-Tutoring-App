<?php
  session_start();
  // Gather login credentials
  $gtid = htmlspecialchars($_POST["user_gtid"]);
  $password = htmlspecialchars($_POST["password"]);

  $database = "4400_project_db";
  $con = mysql_connect(localhost, "root", "mysql");
  @mysql_select_db($database) or die( "Unable to select database");

  $count = getProfessorByGTID($gtid, $password);
  if ($count == 0) $count = getAdministratorByGTID($gtid, $password);
  if ($count == 0) $count = getTutorByGTID($gtid, $password);
  if ($count == 0) $count = getUndergradByGTID($gtid, $password);
  if ($count == 0) $count = getGradByGTID($gtid, $password);
  if ($count == 0) createLoginErrorMsg();
  else redirectToMenu($gtid );

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

    if ($rowCount != 0) {
      $_SESSION['userType'] = 'tutor';
      $count = getUndergradByGTID($gtid, $password);
      if ($count == 0) getGradByGTID($gtid, $password);
    }

    return $rowCount;
  }

  function getUndergradByGTID($gtid, $password) {
    $query = sprintf("SELECT GTID, Password\n" .
                      "FROM Undergraduate AS U\n" .
                      "WHERE U.GTID = '%s' AND U.Password = '%s';",
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

    if ($rowCount != 0) $_SESSION['gradType'] = 'undergrad';
    return $rowCount;
  }

  function getGradByGTID($gtid, $password) {
    $query = sprintf("SELECT GTID, Password\n" .
                     "FROM Graduate AS G\n" .
                     "WHERE G.GTID = '%s' AND G.Password = '%s';",
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

    if ($rowCount != 0) $_SESSION['gradType'] = 'grad';
    return $rowCount;
  }

  function createLoginErrorMsg() {
    $_SESSION['loginError'] = "User does not exist or authentication" .
                              " failed. Please try again.";
    header("Location: ../html/login.html");
    die();
  }

  function redirectToMenu($gtid ) {
    unset($_SESSION['loginError']);
    $_SESSION['user_gtid'] = $gtid;
    header("Location: ../html/menu.html");
    die();
  }
?>