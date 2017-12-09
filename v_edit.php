<?php
/*
Author: Javed Ur Rehman
Website: https://www.allphptricks.com/
*/

$db_host = "rds-mysql-ehr-project.crftk2votri2.us-east-2.rds.amazonaws.com"; //Took out :3306
	$db_user = "jackelalien";
	$db_pass = "csce513-net";
	$db_name = "EHR_Networking";
	

	$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	
	if($con->connect_error)
	{
		echo "Connection Failed.";
		die("Connection Failed: " . $con->connect_error);
	}
	
include("auth.php");
$id=$_REQUEST['id'];
$query = "SELECT * from Patients where Patient_ID='".$id."'";
$result = mysqli_query($con, $query) or die ( mysqli_error());
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Update Record</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="form">
<p><a href="index2.php">Dashboard</a> | <a href="insert.php">Insert New Record</a> | <a href="logout.php">Logout</a></p>
<h1>Update Record</h1>
<?php
$status = "";
if(isset($_POST['new']) && $_POST['new']==1)
{
$id=$_REQUEST['id'];
$diagnosis =$_REQUEST['diagnosis'];
$treatment =$_REQUEST['treatment'];
$high =$_REQUEST['high'];
$low =$_REQUEST['low'];


$submittedby = $_SESSION["username"];
$update="update Patients set Diagnosis='".$diagnosis."', Treament='".$treatment."', Treament_High_Threshold=".$high.", Treament_Low_Threshold=".$low." where Patient_ID='".$id."'";
mysqli_query($con, $update) or die(mysqli_error());
$status = "Record Updated Successfully. </br></br><a href='view.php'>View Updated Record</a>";
echo '<p style="color:#FF0000;">'.$status.'</p>';
}else {
?>
<div>
<form name="form" method="post" action="">
<input type="hidden" name="new" value="1" />
<input name="id" type="hidden" value="<?php echo $row['Patient_ID'];?>" />

<p><input type="text" name="diagnosis" placeholder="Enter Diagnosis" required value="<?php echo $row['Treament'];?>" /></p>
<p><input type="text" name="treatment" placeholder="Enter Treatment" required value="<?php echo $row['Diagnosis'];?>" /></p>
<p><input type="number" name="high" placeholder="Enter High Threshold" required value="<?php echo $row['Treatment_High_Threshold'];?>" /></p>
<p><input type="number" name="low" placeholder="Enter Low Threshold" required value="<?php echo $row['Treatment_Low_Threshold'];?>" /></p>
<p><input name="submit" type="submit" value="Update" /></p>
</form>
<?php } ?>

<br /><br /><br /><br />
</div>
</div>
</body>
</html>
