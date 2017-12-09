<?php
	$db_host = "rds-mysql-ehr-project.crftk2votri2.us-east-2.rds.amazonaws.com";
	$db_user = "jackelalien";
	$db_pass = "csce513-net";
	$db_name = "EHR_Networking";
	
	//Choices
	//0 - Concern for multiple threshold abuses
	//1 - Adding to the Monitor on the Patient ID
	//2 - Updating the Counter on a similar value.
	
	$choice = intval($_GET['choice']);
	$id = intval($_GET['id']);
	$concern = intval($_GET['concern']);
	$currentValue = $_GET['currVal'];
	

	
	$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	
	if($choice == 0)
	{
		
	}
	
	if($choice == 1)
	{
		$sql = "INSERT into `Monitors` (Monitor_ID, Patient_ID, Current_Value, Counter) VALUES 
			(0, $id, #currentValue, 0)";
		
		$result = mysqli_query($link, $sql);
		
		if($result){
            echo "OKAY";
        }
	}
	
	if($choice == 2)
	{
		
	}
	

?>