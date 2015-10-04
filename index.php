<?php
	include 'php/globals.php';

	$db = dbConnect();
	initAppTables($db);
	populateTables($db);
	
  header('Location: views/login_view.php');

?>