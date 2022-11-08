<!-- 
  NodeMCU RFID System
  Marc Antony Martínez Mejía
  Marlene Medellin González
-->
<?php
/* Database connection settings */
	$servername = "localhost";
    $username = "root";		
    $password = "";	
    $dbname = "nodemculog";
    
	$conn = new mysqli($servername, $username, $password, $dbname);
	global $conn;
	if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
    }
?>