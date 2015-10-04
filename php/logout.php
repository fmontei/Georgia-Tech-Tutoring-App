<?php
  session_start();
  $_SESSION = array();
  session_destroy();
  header("Location: ../views/login_view.php");
  die();
?>