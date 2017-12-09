<?php
/*
Author: Javed Ur Rehman
Website: https://www.allphptricks.com/
*/

$db_host = "rds-mysql-ehr-project.crftk2votri2.us-east-2.rds.amazonaws.com"; //Took out :3306
	$db_user = "jackelalien";
	$db_pass = "csce513-net";
	$db_name = "EHR_Networking";
	

	$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	
	if($link->connect_error)
	{
		echo "Connection Failed.";
		die("Connection Failed: " . $link->connect_error);
	}

include("auth.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>PHP Application - AWS Elastic Beanstalk</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster+Two" type="text/css">
    <link rel="icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
    <link rel="shortcut icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="/styles.css" type="text/css">
</head>
<body>
<div class="form">
<p><a href="index2.php">Home</a> | <a href="insert.php">Insert New Record</a> | <a href="logout.php">Logout</a></p>
<h2>View Records</h2>
<table width="100%" border="1" style="border-collapse:collapse;">
<thead>
<tr><th><strong>S.No</strong></th><th><strong>First Name</strong></th><th><strong>Last Name</strong></th><th><strong>Gender</strong></th><th><strong>DOB</strong></th><th><strong>Diagnosis</strong></th><th><strong>Treatment</strong></th><th><strong>High Thresh</strong></th><th><strong>Low Thresh</strong></th><th><strong>Edit</strong></th><th><strong>Delete</strong></th></tr>
</thead>
<tbody>
<?php

$count=1;
$sel_query="Select * from Patients ORDER BY Patient_ID;";

$result = mysqli_query($link,$sel_query);
while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td align="center"><?php echo $count; ?></td>
<td align="center"><?php echo $row["First_Name"]; ?></td>
<td align="center"><?php echo $row["Last_Name"]; ?></td>
<td align="center"><?php echo $row["Gender"]; ?></td>
<td align="center"><?php echo $row["Patient_DOB_Date"]; ?></td>
<td align="center"><?php echo $row["Diagnosis"]; ?></td>
<td align="center"><?php echo $row["Treatment"]; ?></td>
<td align="center"><?php echo $row["Treatment_High_Threshold"]; ?></td>
<td align="center"><?php echo $row["Treatment_Low_Threshold"]; ?></td>
<td align="center"><a href="viewSpecific.php?id=<?php echo $row["Patient_ID"]; ?>&fName=<?php echo $row["First_Name"];?>&lName=<?php echo $row["Last_Name"];?>" > Select </a></td>
<td align="center"><a href="v_edit.php?id=<?php echo $row["Patient_ID"]; ?>">Edit</a></td>
<td align="center"><a href="delete.php?id=<?php echo $row["Patient_ID"]; ?>">Delete</a></td>
</tr>
<?php $count++; } ?>
</tbody>
</table>

<br /><br /><br /><br />
</div>
</body>
</html>
