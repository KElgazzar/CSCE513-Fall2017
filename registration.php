<?php
/*
Author: Javed Ur Rehman
Website: http://www.allphptricks.com/
*/
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
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
	
    // If form submitted, insert values into the database.
    if (isset($_REQUEST['username'])){
		
		$fName = stripslashes($_REQUEST['fName']); // removes backslashes
		$fName = mysqli_real_escape_string($con,$fName);
		
		$lName = stripslashes($_REQUEST['lName']); // removes backslashes
		$lName = mysqli_real_escape_string($con,$lName);
		
		$dob = date('m/d/Y', strtotime($_POST['DOB']));
		
		$username = stripslashes($_REQUEST['username']); // removes backslashes
		$username = mysqli_real_escape_string($con,$username); //escapes special characters in a string
		$email = stripslashes($_REQUEST['email']);
		$email = mysqli_real_escape_string($con,$email);
		$password = stripslashes($_REQUEST['password']);
		$password = mysqli_real_escape_string($con,$password);
		
		$option = $_POST['gender'];
		$gender = 0;
		if($option == "male")
		{
			$gender = 0;
		}
		else
		{
			$gender = 1;
		}
		
		$phone = stripslashes($_REQUEST['phone']);
		$phone = mysqli_real_escape_string($con,$phone);
		
		$mail = stripslashes($_REQUEST['mail']);
		$mail = mysqli_real_escape_string($con,$mail);
		
		$city = stripslashes($_REQUEST['city']);
		$city = mysqli_real_escape_string($con,$city);
		
		$state = stripslashes($_REQUEST['state']);
		$state = mysqli_real_escape_string($con,$state);
		
		$zip = stripslashes($_REQUEST['zip']);
		$zip = mysqli_real_escape_string($con,$zip);
		
		$docLast = stripslashes($_REQUEST['docLName']);
		$docLast = mysqli_real_escape_string($con,$docLast);
		
		
		if(isset($_POST['isdoc']))
		{
			$query = "INSERT into `Doctors` (Doc_ID, First_Name, Last_Name, Username, Doc_DOB_Date, Password) VALUES 
			(0, '$fName', '$lName', '$username', '$dob', '".md5($password)."')";
        $result = mysqli_query($con,$query);
        if($result){
            echo "<div class='form'><h3>You are registered successfully.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
        }
		}
		else
		{
			$finalMail = $mail . " " . $city . ", " . $state . " " . $zip;
			$foundDoc = 0;
			
			$sql = "SELECT Doc_ID FROM `Doctors` WHERE Last_Name='$docLast'";
			$result = mysqli_query($con, $sql);
		
			if(mysqli_num_rows($result) > 0)
			{
				$row = mysqli_fetch_row($result);
				$foundDoc = $row[0];
			}
			
			
			$query = "INSERT into `Patients` (Patient_ID, First_Name, Last_Name, Username, Password, 
			PhoneNumber, MailingAddress, EmailAddress, Patient_DOB_Date, Gender, Doc_ID, Diagnosis, Treatment, Treatment_High_Threshold, Treatment_Low_Threshold, 
			RetrievalRate, Concern) VALUES 
			(0, '$fName', '$lName', '$username', '".md5($password)."', $phone, '$finalMail', '$email', '$dob', $gender, $foundDoc, 'x', 'x', 0, 0, 0, 0)";
        $result = mysqli_query($con,$query);
        if($result){
            echo "<div class='form'><h3>You are registered successfully.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
        }
		else
		{
			echo "FAILED: " . mysqli_error($con);
		}
		}
		

    }else{
?>
<div class="form">
<h1>Registration</h1>
<form name="registration" action="" method="post">
<input type="checkbox" name="isdoc" name="docRegister" /> Register as Doctor<br/>
<br>As a Doctor, only First Name, Last Name, Username, DOB, and Password are required. Patients require all data</br>
<br>Basic Information</br>
<input type="text" name="fName" placeholder="First Name" required />
<input type="text" name="lName" placeholder="Last Name" required />
Birthday: <input type="date" name="DOB" required />
<input type="radio" name="gender" value="male"/> Male <br/>
<input type="radio" name="gender" value="female"/> Female <br/>


<br/>
<br>Contact Information</br>
<input type="email" name="email" placeholder="Email"  />
<input type="number" name="phone" placeholder="Phone Number" />
<input type="text" name="mail" placeholder="Mailing Address" />
<input type="text" name="city" placeholder="City" />
<input type="text" name="state" placeholder="State" />
<input type="number" name="zip" placeholder="Zip" />

<br/>
<br>Personal Information</br>
<input type="text" name="docLName" placeholder="Doctor's Last Name" />
<input type="text" name="username" placeholder="Username" required />
<input type="password" name="password" placeholder="Password" required />
<br/>


<input type="submit" name="submit" value="Register" />
</form>
<br /><br />

</div>
<?php } ?>
</body>
</html>
