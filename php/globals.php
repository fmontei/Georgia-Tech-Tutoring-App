<?php  
  function dbConnect() {
  	$dbopts = parse_url(getenv('HEROKU_POSTGRESQL'));
  	$dsn = 'pgsql:'
  			. 'host=' . $dbopts['host'] . ';'
  			. 'dbname=' . ltrim($dbopts['path'], '/') . ';'
  			. 'user=' . $dbopts['user'] . ';'
  			. 'port=' . $dbopts['port'] . ';'
  			. 'password=' . $dbopts['pass'] . ';';
  
  	try {
  		$db = new PDO($dsn);
  		echo 'Successly connected to db.<br />';
  		return $db;
  	} catch(PDOException $e) {
  		print_r($errorInfo);
  		echo '<br />';
  		die();
  	}
  }
  
  function initAppTables($db) {
  	$queries = array(
  		0 => 'CREATE TABLE Administrator (
							GTID  CHAR(9), 
							Password VARCHAR(30) NOT NULL,
							PRIMARY KEY (GTID) 
  					);',
  		1 => 'CREATE TABLE Student (
							GTID  CHAR(9), 
							Password VARCHAR(30) NOT NULL,
							Email VARCHAR(40) NOT NULL,
							Name VARCHAR(100) NOT NULL,
							PRIMARY KEY (GTID),
							UNIQUE(Email) 
  					);',
  		2 => 'CREATE TABLE Graduate (
							GTID CHAR(9),
							Password VARCHAR(30) NOT NULL,
							PRIMARY KEY (GTID),
							FOREIGN KEY (GTID) REFERENCES Student (GTID) 
  					);',
  		3 => 'CREATE TABLE Undergraduate (
							GTID CHAR(9),
							Password VARCHAR(30) NOT NULL,
							PRIMARY KEY (GTID),
							FOREIGN KEY (GTID) REFERENCES Student (GTID) 
						);',
  		4 => 'CREATE TABLE Tutor (
							GTID CHAR(9),
							Password VARCHAR(30) NOT NULL,
							Phone CHAR(10),
							GPA DECIMAL(3, 2) NOT NULL,
							CHECK (GPA >= 3.00),
							PRIMARY KEY (GTID) 
  					);',
  		5 => 'CREATE TABLE Course (
							School VARCHAR (100),
							Number INT,
							PRIMARY KEY (School, Number) 
						);',
  		6 => 'CREATE TABLE Professor ( 
							GTID CHAR(9),
							Password VARCHAR(30) NOT NULL,
							PRIMARY KEY (GTID) 
  					);',
  		7 => 'CREATE TABLE Tutor_Time_Slot (
							GTID CHAR(9),
							Time VARCHAR(8),
							Semester VARCHAR(6),
							Weekday VARCHAR(10),
							PRIMARY KEY (GTID, Time, Semester, Weekday),
							FOREIGN KEY (GTID) REFERENCES Student (GTID) 
  					);',
  		8 => 'CREATE TABLE Recommends ( 
							GTID_Tutor CHAR(9),
							GTID_Professor CHAR (9),
							Num_Evaluation INT,
							Desc_Evaluation VARCHAR(1000),
							CHECK (Num_Evaluation >= 1 AND Num_Evaluation <= 4),
							PRIMARY KEY (GTID_Tutor, GTID_Professor),
							FOREIGN KEY (GTID_Tutor) REFERENCES Tutor (GTID),
							FOREIGN KEY (GTID_Professor) REFERENCES Professor (GTID) 
  					);',
  		9 => 'CREATE TABLE Tutors (
							GTID_Tutor CHAR(9),
							School VARCHAR(100),
							Number INT,
							GTA BOOLEAN,
							PRIMARY KEY (GTID_Tutor, School, Number),
							FOREIGN KEY (GTID_Tutor) REFERENCES Tutor (GTID), 
							FOREIGN KEY (School, Number) REFERENCES Course (School, Number) 
  					);',
  		10 => 'CREATE TABLE Rates (
							GTID_Undergraduate CHAR (9),
							GTID_Tutor CHAR (9),
							School VARCHAR (100),
							Number INT,
							Num_Evaluation INT,
							Desc_Evaluation VARCHAR(1000),
							CHECK (Num_Evaluation >= 1 AND Num_Evaluation <= 4),
							PRIMARY KEY (GTID_Undergraduate, GTID_Tutor),
							FOREIGN KEY (GTID_Tutor) REFERENCES Tutor (GTID),
							FOREIGN KEY (GTID_Undergraduate) REFERENCES Undergraduate (GTID) 
  					);',
  		11 => 'CREATE TABLE Hires (
							GTID_Undergraduate CHAR (9),
							GTID_Tutor CHAR (9),
							School VARCHAR (100),
							Number INT,
							Time VARCHAR(8),
							Semester VARCHAR(6),
							Weekday VARCHAR(10), 
							PRIMARY KEY (GTID_Tutor, Time, Semester, Weekday),
							FOREIGN KEY (GTID_Undergraduate) REFERENCES Undergraduate (GTID),
							FOREIGN KEY (GTID_Tutor, School, Number) REFERENCES Tutors (GTID_Tutor, School, Number),
							FOREIGN KEY (GTID_Tutor, Time, Semester, Weekday) REFERENCES Tutor_Time_Slot (GTID, Time, Semester, Weekday) 
  					);'  			
  	);
  	foreach ($queries as $query) {
  		$result = $db->query($query);
  		queryErrorHandler($db, $result);
  	} 	
  }
  
  function populateTables($db) {
  	$queries = array(
  		'Administrator' => 'SELECT count(*) FROM Administrator;',
  		'Student' => 'SELECT count(*) FROM Student;',
  		'Professor' => 'SELECT count(*) FROM Professor;',
  		'Undergraduate' => 'SELECT count(*) FROM Undergraduate',
  		'Graduate' => 'SELECT count(*) FROM Graduate',
  		'Course' => 'SELECT count(*) FROM Course',
  		'Tutor' => 'SELECT count(*) FROM Tutor',
  		'Tutors' => 'SELECT count(*) FROM Tutors',
  		'Tutor_Time_Slot' => 'SELECT count(*) FROM Tutor_Time_Slot',
  		'Hires' => 'SELECT count(*) FROM Hires',
  		'Rates' => 'SELECT count(*) FROM Rates',
  		'Recommends' => 'SELECT count(*) FROM Recommends'
  	);
  	foreach ($queries as $query) {
  		$result = $db->query($query);
  		$retval = queryErrorHandler($db, $result);
  		if ($retval === true) {
  			$count = 0;
	  		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  		$count = trim($row['count']);
		  		break;
		  	}
		  	if ($count == 0) {
		  		$key = array_search($query, $queries);
		  		populateTableByName($db, $key);
		  	}
  		}
  	}
  }
  
  function populateTableByName($db, $name) {
  	$queries = array(
  		'Administrator' => "INSERT INTO Administrator (GTID, Password) VALUES
										('000000000', '000000000');",
  		'Student' => "INSERT INTO Student (GTID, Password, Email, Name) VALUES
										('000000001', '000000001', 'jsauer@gmail.com', 'Jerome Sauer'),
										('000000002', '000000002', 'emachin@gmail.com', 'Elodia Machin'),
										('000000003', '000000003', 'smazzota@gmail.com', 'Spring Mazzota'),
										('000000004', '000000004', 'emcmaster@gmail.com', 'Elanor Mcmaster'),
										('000000005', '000000005', 'dvigna@gmail.com', 'Darcy Vigna'),
										('000000006', '000000006', 'csylvester@gmail.com', 'Cathi Sylvester'),
										('000000007', '000000007', 'tfiller@gmail.com', 'Temple Filler'),
										('000000008', '000000008', 'jcolosimo@gmail.com', 'Jen Colosimo'),
										('000000009', '000000009', 'twoodall@gmail.com', 'Tama Woodall'),
										('000000010', '000000010', 'tcammarata@gmail.com', 'Terra Cammarata'),
										('000000011', '000000011', 'nmcneeley@gmail.com', 'Naoma Mcneeley'),
										('000000012', '000000012', 'kduryea@gmail.com', 'Kandis Duryea'),
										('000000013', '000000013', 'rmariott@gmail.com', 'Renaldo Mariott'),
										('000000014', '000000014', 'cbeirne@gmail.com', 'Caryn Beirne'),
										('000000015', '000000015', 'sstack@gmail.com', 'Shan Stack'),
										('000000016', '000000016', 'hbolen@gmail.com', 'Hiedi Bolen'),
										('000000017', '000000017', 'mleavell@gmail.com', 'Malvina Leavell'),
										('000000018', '000000018', 'ldacosta@gmail.com', 'Lincoln Dacosta'),
										('000000019', '000000019', 'cvasconcellos@gmail.com', 'Carly Vasconcellos'),
										('000000020', '000000020', 'ssans@gmail.com', 'September Sans');",
  		'Professor' => "INSERT INTO Professor (GTID, Password) VALUES
										('000000021', '000000021'),
										('000000022', '000000022'),
										('000000023', '000000023'),
										('000000024', '000000024');",
  		'Undergraduate' => "INSERT INTO Undergraduate (GTID, Password) VALUES
										('000000006', '000000006'),
										('000000007', '000000007'),
										('000000008', '000000008'),
										('000000009', '000000009'),
										('000000010', '000000010'),
										('000000011', '000000011'),
										('000000012', '000000012'),
										('000000013', '000000013'),
										('000000014', '000000014'),
										('000000015', '000000015'),
										('000000016', '000000016'),
										('000000017', '000000017'),
										('000000018', '000000018'),
										('000000019', '000000019'),
										('000000020', '000000020');",
  			'Graduate' => "INSERT INTO Graduate (GTID, Password) VALUES
						  			('000000001', '000000001'),
						  			('000000002', '000000002'),
						  			('000000003', '000000003'),
						  			('000000004', '000000004'),
						  			('000000005', '000000005');",
  			'Course' => "INSERT INTO Course (School, Number) VALUES
						  			('ECE', 1000),
						  			('ECE', 2000),
						  			('ECE', 3000),
						  			('ECE', 4000),
						  			('ECE', 5000);",
  			'Tutor' => "INSERT INTO Tutor (GTID, Password, Phone, GPA) VALUES
						  			('000000004', '000000004', '4045555555', 3.5),
						  			('000000005', '000000005', '4046666666', 3.6),
						  			('000000006', '000000006', '4041010101', 3.1),
						  			('000000007', '000000007', '4047777777', 3.7),
						  			('000000008', '000000008', '4048888888', 3.8),
						  			('000000009', '000000009', '4049999999', 3.9);",
  			'Tutors' => "INSERT INTO Tutors (GTID_tutor, School, Number, GTA) VALUES
						  			('000000004', 'ECE', 1000, TRUE),
						  			('000000005', 'ECE', 2000, FALSE),
						  			('000000006', 'ECE', 5000, FALSE),
						  			('000000007', 'ECE', 3000, FALSE),
						  			('000000008', 'ECE', 4000, FALSE),
						  			('000000009', 'ECE', 5000, FALSE);",
  			'Tutor_Time_Slot' => "INSERT INTO Tutor_Time_Slot (GTID, Time, Semester, Weekday) VALUES
						  			('000000004', '9am', 'FALL', 'Monday'),
						  			('000000004', '10am', 'FALL', 'Monday'),
						  			('000000004', '11am', 'FALL', 'Monday'),
						  			('000000005', '1pm', 'FALL', 'Tuesday'),
						  			('000000005', '2pm', 'FALL', 'Tuesday'),
						  			('000000005', '3pm', 'FALL', 'Tuesday'),
						  			('000000007', '1pm', 'FALL', 'Tuesday'),
						  			('000000007', '2pm', 'FALL', 'Tuesday'),
						  			('000000007', '3pm', 'FALL', 'Tuesday'),
						  			('000000008', '2pm', 'FALL', 'Wednesday'),
						  			('000000008', '3pm', 'FALL', 'Wednesday'),
						  			('000000008', '4pm', 'FALL', 'Wednesday'),
						  			('000000009', '9am', 'FALL', 'Thursday'),
						  			('000000009', '10am', 'FALL', 'Thursday'),
						  			('000000009', '11am', 'FALL', 'Thursday'),
						  			('000000006', '1pm', 'FALL', 'Friday'),
						  			('000000006', '2pm', 'FALL', 'Friday'),
						  			('000000006', '3pm', 'FALL', 'Friday'),
						  			('000000004', '9am', 'SPRING', 'Monday'),
						  			('000000004', '10am', 'SPRING', 'Monday'),
						  			('000000004', '11am', 'SPRING', 'Monday'),
						  			('000000005', '1pm', 'SPRING', 'Tuesday'),
						  			('000000005', '2pm', 'SPRING', 'Tuesday'),
						  			('000000005', '3pm', 'SPRING', 'Tuesday'),
						  			('000000007', '1pm', 'SPRING', 'Tuesday'),
						  			('000000007', '2pm', 'SPRING', 'Tuesday'),
						  			('000000007', '3pm', 'SPRING', 'Tuesday'),
						  			('000000008', '2pm', 'SPRING', 'Wednesday'),
						  			('000000008', '3pm', 'SPRING', 'Wednesday'),
						  			('000000008', '4pm', 'SPRING', 'Wednesday'),
						  			('000000009', '9am', 'SPRING', 'Thursday'),
						  			('000000009', '10am', 'SPRING', 'Thursday'),
						  			('000000009', '11am', 'SPRING', 'Thursday'),
						  			('000000006', '1pm', 'SPRING', 'Friday'),
						  			('000000006', '2pm', 'SPRING', 'Friday'),
						  			('000000006', '3pm', 'SPRING', 'Friday'),
						  			('000000005', '1pm', 'SUMMER', 'Tuesday'),
						  			('000000005', '2pm', 'SUMMER', 'Tuesday'),
						  			('000000005', '3pm', 'SUMMER', 'Tuesday'),
						  			('000000007', '1pm', 'SUMMER', 'Tuesday'),
						  			('000000007', '2pm', 'SUMMER', 'Tuesday'),
						  			('000000007', '3pm', 'SUMMER', 'Tuesday'),
						  			('000000006', '9am', 'FALL', 'Thursday');",
	  			'Hires' => "INSERT INTO Hires (GTID_Undergraduate, GTID_Tutor, School, Number, Time, Semester, Weekday) VALUES
						  			('000000006', '000000007', 'ECE', 3000, '1pm', 'SPRING', 'Tuesday'),
						  			('000000006', '000000004', 'ECE', 1000, '10am', 'FALL', 'Monday'),
						  			('000000007', '000000004', 'ECE', 1000, '9am', 'FALL', 'Monday'),
						  			('000000008', '000000007', 'ECE', 3000, '3pm', 'SUMMER', 'Tuesday'),
						  			('000000012', '000000009', 'ECE', 5000, '10am', 'SPRING', 'Thursday'),
						  			('000000015', '000000008', 'ECE', 4000, '2pm', 'SPRING', 'Wednesday');",
  				'Rates' => "INSERT INTO Rates (GTID_Undergraduate, GTID_Tutor, School, Number, Num_Evaluation, Desc_Evaluation) VALUES
						  			('000000011', '000000004', 'ECE', 1000, 3, 'Nice guy.'),
						  			('000000012', '000000005', 'ECE', 2000, 1, 'Terrible.'),
						  			('000000013', '000000007', 'ECE', 3000, 2, 'Bad.'),
						  			('000000014', '000000008', 'ECE', 4000, 4, 'Very good.'),
						  			('000000015', '000000009', 'ECE', 5000, 1, 'Pretty Bad.'),
						  			('000000016', '000000006', 'ECE', 5000, 4, 'Excellent.'),
						  			('000000016', '000000004', 'ECE', 1000, 2, 'Nice guy.'),
						  			('000000015', '000000005', 'ECE', 2000, 2, 'Terrible.'),
						  			('000000014', '000000007', 'ECE', 3000, 1, 'Bad.'),
						  			('000000013', '000000008', 'ECE', 4000, 3, 'Very good.'),
						  			('000000012', '000000009', 'ECE', 5000, 2, 'Pretty Bad.'),
						  			('000000011', '000000006', 'ECE', 5000, 3, 'Excellent.');",
			  	'Recommends' => "INSERT INTO Recommends (GTID_Tutor, GTID_Professor, Num_Evaluation, Desc_Evaluation) VALUES
						  			('000000004', '000000021', 1, 'Hard worker.'),
						  			('000000005', '000000021', 2, 'Nice worker.'),
						  			('000000007', '000000022', 3, 'Gentle worker.'),
						  			('000000008', '000000023', 3, 'Soft worker.'),
						  			('000000009', '000000024', 2, 'Real worker.'),
						  			('000000006', '000000024', 1, 'Caring worker.'),
						  			('000000006', '000000021', 3, 'Hard worker.'),
						  			('000000009', '000000021', 2, 'Nice worker.'),
						  			('000000008', '000000022', 1, 'Gentle worker.'),
						  			('000000007', '000000023', 1, 'Soft worker.'),
						  			('000000005', '000000024', 2, 'Real worker.'),
						  			('000000004', '000000024', 3, 'Caring worker.');"
  	);
  	$query = $queries[$name];
  	$result = $db->query($query);
  	queryErrorHandler($db, $result);
  }
  
  function queryErrorHandler($db, $result) {
  	if ($result === false) {
  		$errorInfo = $db->errorInfo();
  		print_r($errorInfo);
  		echo '<br />';
  		return false;
  	}
  	return true;
  }
?>