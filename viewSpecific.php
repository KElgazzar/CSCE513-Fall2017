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

	$id = intval($_GET['id']);
	$firstN = $_GET['fName'];
	$lastN = $_GET['lName'];

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
<p><a href="index2.php">Home</a> | <a href="view.php">Back</a> | <a href="logout.php">Logout</a></p>
<h2>Viewing Patient Records For <?php echo $firstN; echo " "; echo $lastN; ?></h2>
<table width="100%" border="1" style="border-collapse:collapse;">
<thead>
<tr><th><strong>Monitor_ID</strong></th><th><strong>Current Value</strong></th></tr>
</thead>
<tbody>
<?php

$count=1;
$sel_query="Select * from Monitors WHERE Patient_ID=".$id;

$result = mysqli_query($link,$sel_query);
while($row = mysqli_fetch_assoc($result)) { ?>
<tr><td align="center"><?php echo $row["Monitor_ID"]; ?></td><td align="center"><?php echo $row["Current_Value"]; ?></td></tr>
</tbody>
</table>
<?php } ?>
<br /><br /><br /><br />
</div>
</body>
</html>
