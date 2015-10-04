<?php 
  include 'globals.php';

  session_start();
  $db = dbConnect();

  $tutorIDs = fetchAllTutorIDs($db);
  storeAllTutorIDs($tutorIDs);
  header('Location: ../views/tutor_schedule_view.php');
  die();

  function fetchAllTutorIDs($db) {
    $query = 'SELECT DISTINCT gtid FROM Tutor ORDER BY gtid ASC';
    $result = $db->query($query);
    return $result;
  }

  function storeAllTutorIDs($tutorIDs) {
    $availableTutorIDs = [];
    while ($row = $tutorIDs->fetch(PDO::FETCH_ASSOC)) {
      array_push($availableTutorIDs, $row['gtid']);
    }
    $_SESSION['availableTutorIDs'] = $availableTutorIDs;  
  }
    
?>