<?php 
$username = "myorgdatabase"; 
$password = "myorgdatabase"; 
$hostname = "myorgdatabase.cbaqu2q4o6ky.us-east-1.rds.amazonaws.com"; 
$dbname = "myorgdatabase";

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) or die("Unable to connect to MySQL"); 
echo "Connected to MySQL using username - $username, password - $password, host - $hostname<br>"; 
$selected = mysql_select_db("$dbname",$dbhandle)   or die("Unable to connect to MySQL DB - check the database name and try again."); 
?>
