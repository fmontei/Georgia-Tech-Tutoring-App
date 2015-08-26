<?php 
  include 'globals.php';

  session_start();
  db_connect(); // From globals.php

  $tutorIDs = fetchAllTutorIDs();
  storeAllTutorIDs($tutorIDs);
  header('Location: ../html/tutor_schedule.html');
  die();

  function fetchAllTutorIDs() {
    $query = sprintf('SELECT GTID FROM Tutor');
    $result = mysql_query($query);
    return $result;
  }

  function storeAllTutorIDs($tutorIDs) {
    $availableTutorIDs = [];
    while ($row = mysql_fetch_assoc($tutorIDs)) {
      array_push($availableTutorIDs, $row['GTID']);
    }
    $_SESSION['availableTutorIDs'] = $availableTutorIDs;  
  }
    
?>