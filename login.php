<?php

   $errorMsg = "";

	$validUser = "";

    $validUser = !empty($_POST['value']) ? $_POST['value'] : '';

    $error = "";

    $error = !empty($_POST['value']) ? $_POST['value'] : '';

    $_POST["sub"] = "";

    $_POST["sub"] = !empty($_POST['value']) ? $_POST['value'] : '';

    //error_reporting( error_reporting() & ~E_NOTICE )

	error_reporting(0);
	@ini_set('display_errors', 0);



if(isset($_POST["sub"])) {
  
  $validUser = $_POST["username"] == "admin" && $_POST["password"] == "password";

  if(!$validUser) $error = "Invalid username or password.";

  if($validUser) {

        header("location: selectPatient.php"); die();

  }

}





?>

<html>



   <head>

      <title>Login Page</title>



      <style type = "text/css">

         body {

            font-family:Arial, Helvetica, sans-serif;

            font-size:14px;

         }



         label {

            font-weight:bold;

            width:100px;

            font-size:14px;

         }



         .box {

            border:#666666 solid 1px;

         }

      </style>



   </head>



   <body bgcolor = "#FFFFFF">



      <div align = "center">

         <div style = "width:300px; border: solid 1px #333333; " align = "left">

            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login Page</b></div>



            <div style = "margin:30px">



               <form action = "" method = "post">

                  <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />

                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />

                  <input type="submit" value="Submit" name="sub" />

               </form>



               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>



            </div>



         </div>



      </div>



   </body>

</html>
