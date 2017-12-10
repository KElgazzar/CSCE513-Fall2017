<html>



<table>



<tr>



<td>Name</td>

<td>Age</td>

</tr>



<?php

    $username = "";

    $username = !empty($_POST['value']) ? $_POST['value'] : '';

    $password = "";

    $password = !empty($_POST['value']) ? $_POST['value'] : '';

    $table = "";

    $table = !empty($_POST['value']) ? $_POST['value'] : '';

    $db = "";

    $db = !empty($_POST['value']) ? $_POST['value'] : '';

    $stmt = "";

    $stmt = !empty($_POST['value']) ? $_POST['value'] : '';

    $rows = "";

    $rows = !empty($_POST['value']) ? $_POST['value'] : '';

    error_reporting(0);

    @ini_set('displaying_errors', 0);

    //error_reporting( error_reporting() & ~E_NOTICE )

// Enter username and password

$username = root;

$password = pass;



// Create database connection using PHP Data Object (PDO)

$db = new PDO("mysql:host=localhost;dbname=heartrate", $username, $password);



$table = 'patient';



// Create the query - here we grab everything from the table

$stmt = $db->query('SELECT * from '.$table);



// Close connection to database

$db = NULL;



while($rows = $stmt->fetch()){

echo "<tr><td>";

echo $rows['Name'];

echo "<td>";

echo $rows['Age'];

echo "<td>";

?>

<form action="heartratedatarefresh.php" method="post">

<input type='submit' name='submit' value='Select' class='register' />

</form>



<?php

}

echo "</td></tr>";

?>

</table>

</html>
