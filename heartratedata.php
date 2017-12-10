<html>

<table>

<tr>

<td>Time</td>
<td>Rate</td>
</tr>
<?php

// Enter username and password
$username = root;
$password = pass;

// Create database connection using PHP Data Object (PDO)
$db = new PDO("mysql:host=localhost;dbname=heartrate", $username, $password);

// Identify name of table within database
$table = 'heartrate';

// Create the query - here we grab everything from the table
$stmt = $db->query('SELECT * from '.$table);

// Close connection to database
$db = NULL;

while($rows = $stmt->fetch()){
echo "<tr><td>". $rows['Time'] . "</td><td>" . $rows['Rate'] . "</td></tr>";
};
?>
</table>
</html>
