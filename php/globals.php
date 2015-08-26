<?php
  function db_connect() {
    $database = "GT-Tutor-App";
    $con = mysql_connect("georgia-tech-tutor-app.cijyhxa0crol.us-east-1.rds.amazonaws.com:3306", "admin", "password");
    @mysql_select_db($database) or die("Unable to select database");
  }
?>