<?php
	$db_host = "rds-mysql-ehr-project.crftk2votri2.us-east-2.rds.amazonaws.com"; //Took out :3306
	$db_user = "jackelalien";
	$db_pass = "csce513-net";
	$db_name = "EHR_Networking";
	
	$choice = intval($_GET['choice']);
	$id = intval($_GET['id']);
	$pass = $_GET['pword'];
	
	$output = "";
	
	$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	
	if($link->connect_error)
	{
		echo "Connection Failed.";
		die("Connection Failed: " . $link->connect_error);
	}
	
	
	//Choices:
	//0 - Get Doc Password for matching, retrieve all doctor passwords
	//1 - Retrieve Patient Name, Thresholds, Diagnosis/Treatment, Retrieval Rate
	if($choice == 0)
	{
		
		$sql = "SELECT * FROM `Doctors` WHERE Password='".md5($password)."'";
		$result = mysqli_query($link, $sql);
		
		if(mysqli_num_rows($result) > 0)
		{
			$output = "TRUE";
		}
		else
		{
			$output = "FALSE";
		}
	
	}
	
	if($choice == 1)
	{
		$sql = "SELECT First_Name, Treatment_High_Threshold, 
		Treatment_Low_Threshold, Diagnosis, Treatment, RetrievalRate FROM Patients WHERE Patient_ID=$id";
		$result = mysqli_query($link, $sql);
		
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_row($result))
			{
				$output = $row[0] . " " . $row[1] . " " . $row[2] . " " . $row[3] . 
					" " . $row[4] . " " . $row[5];
			}
		}
		else
		{
			$output = "FALSE";
		}
		
	}
	
	print $output;


?>