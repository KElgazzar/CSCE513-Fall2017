<html>



<table>



<tr>



<td>Time</td>

<td>Rate</td>

</tr>

<?php

    $username = "";

    $username = !empty($_POST['value']) ? $_POST['value'] : '';

    $password = "";

    $password = !empty($_POST['value']) ? $_POST['value'] : '';

    $db = "";

    $db = !empty($_POST['value']) ? $_POST['value'] : '';

    $table = "";

    $table = !empty($_POST['value']) ? $_POST['value'] : '';

    $table2 = "";

    $table2 = !empty($_POST['value']) ? $_POST['value'] : '';

    $stmt = "";

    $stmt = !empty($_POST['value']) ? $_POST['value'] : '';

    $stmt2 = "";

    $stmt2 = !empty($_POST['value']) ? $_POST['value'] : '';

    $rows2 = "";

    $rows2 = !empty($_POST['value']) ? $_POST['value'] : '';

    $threshold = "";

    $threshold = !empty($_POST['value']) ? $_POST['value'] : '';

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



// Identify name of table within database

$table = 'heartrate';

$table2 = 'threshold';



// Create the query - here we grab everything from the table

$stmt = $db->query('SELECT * from '.$table);

$stmt2 = $db->query('SELECT * from '.$table2);



$rows2 = $stmt2->fetch();

$threshold = $rows2['threshold'];



if (isset($_POST['button1']))

{

    $db->query('DELETE FROM heartrate WHERE rate > 95');

    echo "<body style='background-color:white'>";

}

if (isset($_POST['button2']))

{

    $db->query('UPDATE threshold SET threshold = 110');

    $db->query('DELETE FROM heartrate WHERE rate > 110');

    echo "<body style='background-color:white'>";

}

if (isset($_POST['button3']))

{

    $db->query('UPDATE threshold SET threshold = 120');

    $db->query('DELETE FROM heartrate WHERE rate > 120');

    echo "<body style='background-color:white'>";

}

if (isset($_POST['button4']))

{

    $db->query('UPDATE threshold SET threshold = 85');

    $db->query('DELETE FROM heartrate WHERE rate > 85');

    echo "<body style='background-color:white'>";

}

if (isset($_POST['button5']))

{

    $db->query('UPDATE threshold SET threshold = 95');

    $db->query('DELETE FROM heartrate WHERE rate > 95');

    echo "<body style='background-color:white'>";

}

echo "Threshold heart rate is " . $rows2['threshold'];

// Close connection to database

$db = NULL;



while($rows = $stmt->fetch()){

if($rows['Rate'] > $rows2['threshold']) echo "<body style='background-color:red'>";

echo "<tr><td>". $rows['Time'] . "</td><td>" . $rows['Rate'] . "</td></tr>";

};



?>

<form method="POST" action=''>

<input type="submit" name="button1"  value="Turn Off Alert">

<input type="submit" name="button2"  value="Increase Threshold to 110">

<input type="submit" name="button3"  value="Increase Threshold to 120">

<input type="submit" name="button4"  value="Decrease Threshold to 85">

<input type="submit" name="button5"  value="Reset Threshold to 95">

</form>



<META HTTP-EQUIV=Refresh CONTENT="1">

</table>

</html>
