<?php

$servername = "localhost"; // If I put up to be hosted the host will provide a server name that replaces localhost
$dBUsername = "root";
$dBPassword = "root";
$dBName = "dbcenterbound";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName); // Connects to the DB

if (!$conn) {
	die("Connection failed: ".mysqli_connect_error());
} // Checks if the connection is successful to the DB