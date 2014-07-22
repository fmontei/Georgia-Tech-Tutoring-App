<?php
	session_start();
	
	$database = "4400_project_db";
	$con = mysql_connect("localhost", "root", "mysql");
	@mysql_select_db($database) or die("Unable to select database");


		$user_gtid = $_SESSION['user_gtid'];
		$tutor_gtid = $_GET["tutorGTIDSelection"];
	    $desc_eval = $_GET["desc_eval"];
		$num_eval = $_GET["final_num_eval_input"];
		
		//check if valid GTID
		$query = sprintf("SELECT Tutor.GTID \n" .
						"FROM Tutor \n" .
						"WHERE Tutor.GTID = '%s';",
						mysql_real_escape_string($tutor_gtid));
						
		$result = mysql_query($query);
		if($result){
			$message = 'GTID input doest not correspond to a tutor';
			die($message);
		}
		
		$query = sprintf("INSERT INTO Recommends(GTID_Tutor, GTID_Professor, Num_Evaluation, Desc_Evaluation), " .
				"VALUES('%s', '%s', '%s', '%s');",
				mysql_real_escape_string($tutor_gtid), 
				mysql_real_escape_string($user_gtid), 
				mysql_real_escape_string($num_eval), 
				mysql_real_escape_string($desc_eval));
		$result = mysql_query($query);
		
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
	
?>
